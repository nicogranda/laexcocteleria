const { chromium } = require(process.env.LAEX_PLAYWRIGHT_ROOT || 'playwright');
(async () => {
  const browser = await chromium.launch({headless: true});
  const page = await browser.newPage();
  const errors = [];
  page.on('pageerror', e => errors.push(e.message));
  for (const width of [375, 701, 768, 1024, 1100, 1101, 1200, 1440, 1920]) {
    await page.setViewportSize({width, height: 900});
    await page.goto('http://127.0.0.1:8765/tests/home-layout.php');
    const layout = await page.evaluate(() => ({
      width: document.documentElement.clientWidth,
      scrollWidth: document.documentElement.scrollWidth,
      overflowing: [...document.querySelectorAll('body *')].filter(e => {
        const r = e.getBoundingClientRect();
        return r.width > 0 && (r.right > innerWidth + 1 || r.left < -1);
      }).slice(0, 10).map(e => e.className)
    }));
    if (layout.scrollWidth > layout.width + 1) throw Error(`Scroll horizontal a ${width}px: ${JSON.stringify(layout)}`);
    for (const [i, slug] of ['bodas','eventos-corporativos','cumpleanos','despedidas','graduaciones','eventos-privados'].entries()) {
      const link = page.locator('.services__card-link').nth(i);
      if (await link.getAttribute('href') !== '/cocteleria-para-' + slug) throw Error('Enlace incorrecto: ' + slug);
      if (await link.locator('.services__card-image').count() !== 1) throw Error('La foto no está dentro del enlace');
    }
  }
  if (errors.length) throw Error(errors.join('\n'));
  await browser.close();
  console.log('Inicio sin scroll horizontal y seis fotos enlazadas: OK (375–1920px)');
})().catch(e => { console.error(e); process.exit(1); });
