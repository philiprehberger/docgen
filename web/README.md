# docgen-web

Next.js 16 docs + marketing site for [Docgen](https://docgen.philiprehberger.com).

## Local dev

```bash
npm install
npm run dev          # localhost:3000
```

## Deploy

Builds locally with `output: 'standalone'`, rsyncs to the EC2 host, `pm2 reload`. See `~/projects/income-ops/.guides/new_demo_project.md` § 5.

```bash
cp .env.example .env
# fill in server values
npm run deploy
```

## Routes

Marketing landing, six concept pages, Scalar-powered API reference with a sandbox-key minter, three SDK quickstart pages, templates showcase, downloads, pricing (mocked, honestly framed), about, live status.

## License

MIT.
