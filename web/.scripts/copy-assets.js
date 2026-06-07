const fs = require('fs').promises;
const path = require('path');

const projectRoot = path.join(__dirname, '..');
const publicSrcPath = path.join(projectRoot, 'public');
const publicDestPath = path.join(projectRoot, '.next/standalone/public');

const staticSrcPath = path.join(projectRoot, '.next/static');
const staticDestPath = path.join(projectRoot, '.next/standalone/.next/static');

async function copyFolder(src, dest) {
  try {
    await fs.access(src);
    await fs.cp(src, dest, { recursive: true });
    console.log(`Copied ${src} → ${dest} successfully.`);
  } catch (error) {
    if (error.code === 'ENOENT') {
      console.error(`Source folder not found: ${src}`);
    } else {
      console.error(`Error copying ${src}:`, error.message);
    }
    throw error;
  }
}

async function copyAssets() {
  console.log('Copying public/ and .next/static into standalone build...\n');
  try {
    await copyFolder(publicSrcPath, publicDestPath);
    await copyFolder(staticSrcPath, staticDestPath);
    console.log('\nBuild artifacts ready for deployment (rsync-based)');
  } catch (error) {
    console.error('\nBuild failed:', error.message);
    process.exit(1);
  }
}

copyAssets();
