import type { Plugin } from "@opencode-ai/plugin"

// Debounce timer shared across all qualifying edits. Module-scope, so it
// survives between tool calls within a single opencode process.
let pending: ReturnType<typeof setTimeout> | null = null

const DEBOUNCE_MS = 3000
// Only these tools mutate files on disk; reads/grep/glob/todos do not.
const MUTATING_TOOLS = new Set(["edit", "write"])

export const GraphifyRefreshPlugin: Plugin = async ({ $, directory, client }) => {
  return {
    "tool.execute.after": async (input, output) => {
      const tool = input?.tool
      if (!tool || !MUTATING_TOOLS.has(tool)) return

      // Skip silently if no graph exists yet — don't auto-bootstrap one.
      const hasGraph = await isFile(`${directory}/graphify-out/graph.json`)
      if (!hasGraph) return

      if (pending) clearTimeout(pending)
      pending = setTimeout(async () => {
        pending = null
        try {
          await $`graphify update .`
          await client.app.log({
            body: {
              service: "graphify-refresh",
              level: "debug",
              message: "graphify update . ran after file edits",
            },
          })
        } catch (err) {
          await client.app.log({
            body: {
              service: "graphify-refresh",
              level: "warn",
              message: `graphify update . failed: ${String(err)}`,
            },
          })
        }
      }, DEBOUNCE_MS)
    },
  }
}

// Minimal portable file-exists check. Uses node fs when available; falls back to
// a Bun `$` test to avoid depending on a specific runtime's import graph.
async function isFile(path: string): Promise<boolean> {
  try {
    // @ts-ignore — node:fs is available under both node and bun
    const fs = await import("node:fs")
    return fs.existsSync(path)
  } catch {
    return false
  }
}