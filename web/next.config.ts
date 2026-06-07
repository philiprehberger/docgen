import path from "path";
import type { NextConfig } from "next";

const nextConfig: NextConfig = {
  output: "standalone",
  // Pin the tracing root to this directory so the standalone bundle
  // emits server.js at `.next/standalone/server.js` instead of being
  // wrapped in a workspace subdirectory derived from the monorepo root.
  outputFileTracingRoot: path.join(__dirname),
};

export default nextConfig;
