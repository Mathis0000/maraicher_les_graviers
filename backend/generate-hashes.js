const bcrypt = require('bcryptjs');

async function generateHashes() {
  const admin = await bcrypt.hash('admin123', 10);
  const customer = await bcrypt.hash('customer123', 10);

  console.log('Hash pour admin123:');
  console.log(admin);
  console.log('\nHash pour customer123:');
  console.log(customer);
  console.log('\nCopiez ces hashs dans backend/db/seeds.sql');
}

generateHashes();
