const assert = require('assert');
const mysql = require('mysql2/promise'); 

describe('EcoSteward - Tests Fonctionnels de la base', () => {
  let conn;

  before(async () => {
    conn = await mysql.createConnection({
      host: 'localhost',
      user: 'root',
      password: '', 
      database: 'eco_farm' 
    });
  });

  after(async () => {
    await conn.end();
  });

  it('La connexion à la base doit réussir', async () => {
    const [rows] = await conn.query('SELECT 1 + 1 AS result');
    assert.strictEqual(rows[0].result, 2);
  });

  it('Il doit y avoir au moins 1 produit en base', async () => {
    const [rows] = await conn.query('SELECT COUNT(*) AS total FROM products');
    assert.ok(rows[0].total >= 1);
  });

  it('Chaque produit doit avoir un nom et un prix > 0', async () => {
    const [rows] = await conn.query('SELECT name, price FROM products');
    rows.forEach(p => {
      assert.ok(p.name.length > 0);
      assert.ok(p.price > 0);
    });
  });

  it('Les stocks critiques doivent être bien détectés', async () => {
    const [rows] = await conn.query('SELECT * FROM products WHERE stock < seuil_alerte');
    rows.forEach(p => {
      assert.ok(p.stock < p.seuil_alerte);
    });
  });

  it('Les ventes doivent avoir une quantité et un prix > 0', async () => {
    const [rows] = await conn.query('SELECT quantity, prix_unitaire FROM sales');
    rows.forEach(v => {
      assert.ok(v.quantity > 0);
      assert.ok(v.prix_unitaire > 0);
    });
  });

  it('Les ateliers à venir doivent avoir une date >= aujourd’hui', async () => {
    const [rows] = await conn.query('SELECT workshop_date FROM workshops WHERE workshop_date >= CURDATE()');
    rows.forEach(w => {
      assert.ok(new Date(w.workshop_date) >= new Date());
    });
  });

  it('Les woofers actifs doivent avoir une date de fin future', async () => {
    const [rows] = await conn.query('SELECT end_date FROM woofers WHERE end_date >= CURDATE()');
    rows.forEach(w => {
      assert.ok(new Date(w.end_date) >= new Date());
    });
  });

});
