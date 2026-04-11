import { mount } from '@vue/test-utils'
import { describe, expect, it } from 'vitest'
import OrbitOverlay from '@/Components/Map/OrbitOverlay.vue'

describe('OrbitOverlay', () => {
  const baseProps = {
    x: 100,
    y: 200,
    radius: 80,
    visible: true,
  }

  const items = [
    { id: 1, image: 'https://example.com/1.jpg', label: 'One' },
    { id: 2, image: 'https://example.com/2.jpg', label: 'Two' },
    { id: 3, image: 'https://example.com/3.jpg', label: 'Three' },
    { id: 4, image: 'https://example.com/4.jpg', label: 'Four' },
  ]

  const getTranslateValues = (style: string) => {
    const match = style.match(/translate\(([-\de.+]+)px,\s*([-\de.+]+)px\)/)
    expect(match).toBeTruthy()

    return {
      x: Number(match?.[1]),
      y: Number(match?.[2]),
    }
  }

  it('shows spinner when loading', () => {
    const wrapper = mount(OrbitOverlay, {
      props: { ...baseProps, loading: true, items: [] }
    })
    expect(wrapper.find('.animate-spin').exists()).toBe(true)
  })

  it('renders orbiting icons and links for commodities', () => {
    const wrapper = mount(OrbitOverlay, {
      props: { ...baseProps, loading: false, items, dataType: 'commodities' }
    })
    const links = wrapper.findAll('a')
    expect(links.length).toBe(items.length)

    links.forEach((a, idx) => {
      expect(a.attributes('href')).toBe(`/projects/breedersmap/commodity/${items[idx].id}`)
      const style = a.attributes('style') || ''
      const { x, y } = getTranslateValues(style)

      expect(Math.hypot(x, y)).toBeCloseTo(baseProps.radius, 10)
      expect(style).toContain(`transition-delay: ${idx * 50}ms`)

      const img = a.find('img')
      expect(img.attributes('src')).toBe(items[idx].image)
      expect(img.attributes('alt')).toBe(items[idx].label)
    })
  })

  it('renders links for breeders when dataType=breeders', () => {
    const wrapper = mount(OrbitOverlay, {
      props: { ...baseProps, loading: false, items, dataType: 'breeders' }
    })
    const links = wrapper.findAll('a')
    links.forEach((a, idx) => {
      expect(a.attributes('href')).toBe(`/projects/breedersmap/breeder/${items[idx].id}`)
    })
  })

  it('emits enter and close on hover', async () => {
    const wrapper = mount(OrbitOverlay, {
      props: { ...baseProps, loading: false, items }
    })

    await wrapper.trigger('mouseenter')
    expect(wrapper.emitted('enter')).toBeTruthy()

    await wrapper.trigger('mouseleave')
    expect(wrapper.emitted('close')).toBeTruthy()
  })
})

