import { config } from '@vue/test-utils'
import { vi } from 'vitest'

// Provide a simple Motion component via module mock so imported Motion works
vi.mock('@vueuse/motion', () => ({
  Motion: {
    name: 'Motion',
    template: '<div><slot /></div>'
  }
}))

// Also keep a stub fallback (not strictly necessary once mocked)
config.global.stubs = {
  Motion: {
    template: '<div><slot /></div>'
  }
}

// Silence Vue warn logs during tests
// eslint-disable-next-line no-console
console.warn = (...args) => {
  if (typeof args[0] === 'string' && args[0].includes('Extraneous non-props attributes')) return
  // eslint-disable-next-line no-console
  console.info('[warn suppressed]', ...args)
}
