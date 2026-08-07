// opencode plugin: auto-refresh graphify-out/graph.json after file edits.
// Plain-function form (no @opencode-ai/plugin import) — that import triggers
// opencode's npm dependency installer to fail (no published version "local").

let pending: ReturnType<typeof setTimeout> | null = null

const DEBOUNCE_MS = 3000
const MUTATING_TOOLS = new Set(["edit", "write"])

export const GraphifyRefreshPlugin = async ({ $, directory, client }) => {
  return {
    "tool.execute.after": async (input, output) => {
      const tool = input?.tool
      if (!tool || !MUTATING_TOOLS.has(tool)) return

      // Skip silently if no graph exists yet — don't auto-bootstrap one.
      let hasGraph = false
      try {
        const fs = await import("node:fs")
        hasGraph = fs.existsSync(`${directory}/graphify-out/graph.json`)
      } catch {
        hasGraph = false
      }
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