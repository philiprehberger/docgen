const path = require('path');
const os = require('os');
const projectRoot = path.join(__dirname, '..');

require('dotenv').config({ path: path.join(projectRoot, '.env') });

const fs = require('fs');
const { spawn } = require('child_process');
const { NodeSSH } = require('node-ssh');
const ssh = new NodeSSH();

function expandHome(p) {
  if (!p) return p;
  if (p === '~') return os.homedir();
  if (p.startsWith('~/')) return path.join(os.homedir(), p.slice(2));
  return p;
}

if (process.env.SERVER_PRIVATE_KEY) {
  process.env.SERVER_PRIVATE_KEY = expandHome(process.env.SERVER_PRIVATE_KEY);
}

// ==== VALIDATE ENVIRONMENT VARIABLES ====
const requiredEnvVars = [
  'SERVER_HOST',
  'SERVER_USERNAME',
  'SERVER_PRIVATE_KEY',
  'SERVER_DEST_PATH',
  'SERVER_PM2_PROCESS',
];

const missingVars = requiredEnvVars.filter((v) => !process.env[v]);
if (missingVars.length > 0) {
  console.error('❌ Missing required environment variables:');
  missingVars.forEach((v) => console.error(`   - ${v}`));
  console.error('\n📝 Please check your .env file. See .env.example for reference.');
  process.exit(1);
}

if (!fs.existsSync(process.env.SERVER_PRIVATE_KEY)) {
  console.error(`❌ SSH private key not found: ${process.env.SERVER_PRIVATE_KEY}`);
  process.exit(1);
}

const SERVER = {
  host: process.env.SERVER_HOST,
  username: process.env.SERVER_USERNAME,
  privateKey: process.env.SERVER_PRIVATE_KEY,
  destPath: process.env.SERVER_DEST_PATH,
  pm2Process: process.env.SERVER_PM2_PROCESS,
};

const standalonePath = path.join(projectRoot, '.next/standalone') + '/';
const destRsyncTarget = `${SERVER.username}@${SERVER.host}:${SERVER.destPath}/`;

function runRsync() {
  return new Promise((resolve, reject) => {
    const sshCmd = `ssh -i "${SERVER.privateKey}" -o StrictHostKeyChecking=accept-new`;
    const args = [
      '-az',
      '--delete',
      '--info=stats1',
      '-e', sshCmd,
      standalonePath,
      destRsyncTarget,
    ];
    console.log(`🔄 rsync ${standalonePath} → ${destRsyncTarget}`);
    const child = spawn('rsync', args, { stdio: 'inherit' });
    child.on('error', reject);
    child.on('close', (code) => {
      if (code === 0) resolve();
      else reject(new Error(`rsync exited with code ${code}`));
    });
  });
}

async function reloadPM2() {
  console.log('🔌 Connecting to server for PM2 reload...');
  const privateKeyContent = fs.readFileSync(SERVER.privateKey, 'utf8');
  await ssh.connect({
    host: SERVER.host,
    username: SERVER.username,
    privateKey: privateKeyContent,
  });

  console.log(`♻️  pm2 reload ${SERVER.pm2Process}`);
  const result = await ssh.execCommand(
    `source ~/.nvm/nvm.sh && pm2 reload ${SERVER.pm2Process} --update-env`
  );
  if (result.stdout) console.log(result.stdout);
  if (result.stderr) console.error(result.stderr);
  if (result.code !== 0) throw new Error(`pm2 reload exited ${result.code}`);
  console.log('✅ Deployment complete');
}

(async () => {
  try {
    await runRsync();
    await reloadPM2();
  } catch (err) {
    console.error('❌ Deployment failed:', err.message || err);
    process.exit(1);
  } finally {
    ssh.dispose();
  }
})();
