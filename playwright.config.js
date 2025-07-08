// @ts-check
import { defineConfig, devices } from '@playwright/test';

/**
 * @see https://playwright.dev/docs/test-configuration
 */
export default defineConfig({
  testDir: './tests',
  
  /* Run tests in files in parallel */
  fullyParallel: true,
  
  /* Fail the build on CI if you accidentally left test.only in the source code. */
  forbidOnly: !!process.env.CI,
  
  /* Retry on CI only */
  retries: process.env.CI ? 2 : 0,
  
  /* Opt out of parallel tests on CI. */
  workers: process.env.CI ? 1 : undefined,
  
  /* Reporter to use. See https://playwright.dev/docs/test-reporters */
  reporter: [
    ['html', { 
      outputFolder: 'docs/reports/playwright/',
      open: 'never'
    }],
    ['json', { 
      outputFile: 'docs/reports/test-results/results.json' 
    }],
    ['junit', { 
      outputFile: 'docs/reports/test-results/results.xml' 
    }],
    ['line']
  ],
  
  /* Shared settings for all the projects below. See https://playwright.dev/docs/api/class-testoptions. */
  use: {
    /* Base URL to use in actions like `await page.goto('/')`. */
    baseURL: 'http://localhost:8090',
    
    /* Collect trace when retrying the failed test. See https://playwright.dev/docs/trace-viewer */
    trace: 'on-first-retry',
    
    /* Capture screenshot after each test failure */
    screenshot: 'only-on-failure',
    
    /* Record video for failed tests */
    video: 'retain-on-failure',
    
    /* Browser console detection */
    /* Console messages otomatik olarak test output'una dahil edilir */
  },

  /* Configure projects for major browsers */
  projects: [
    {
      name: 'chromium',
      use: { 
        ...devices['Desktop Chrome'],
        // Chrome console detection için özel ayarlar
        launchOptions: {
          args: [
            '--enable-logging',
            '--log-level=0',
            '--v=1'
          ]
        }
      },
    },

    {
      name: 'firefox',
      use: { 
        ...devices['Desktop Firefox'],
        // Firefox console detection
        launchOptions: {
          firefoxUserPrefs: {
            'devtools.console.stdout.content': true
          }
        }
      },
    },

    {
      name: 'mobile-chrome',
      use: { 
        ...devices['Pixel 5'],
        // Mobile Chrome console detection
        launchOptions: {
          args: [
            '--enable-logging',
            '--log-level=0'
          ]
        }
      },
    },
  ],

  /* Run your local dev server before starting the tests */
  webServer: {
    command: 'echo "Test server should be running on http://localhost:8090"',
    url: 'http://localhost:8090',
    reuseExistingServer: !process.env.CI,
    timeout: 5000,
  },

  /* Test klasörleri ve output ayarları */
  outputDir: 'docs/reports/test-results/',
  
  /* Global setup for console detection */
  globalSetup: require.resolve('./tests/setup/global-setup.js'),
  
  /* Test timeout ayarları */
  timeout: 30000,
  expect: {
    timeout: 5000,
  },
}); 