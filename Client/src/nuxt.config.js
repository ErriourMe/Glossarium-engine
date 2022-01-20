import { bootstrapConfig } from './config/bootstrapConfig'
import { momentConfig } from './config/momentConfig'
import { toastConfig } from './config/toastConfig'
import { svgSpriteConfig } from './config/svgSpriteConfig'
// import { yandexMetrikaConfig } from './config/yandexMetrikaConfig'

// const env = require('dotenv').config()?.parsed

export default {
  render: {
    resourceHints: false,
    bundleRenderer: {
      shouldPreload: (file, type) => {
        return ['script', 'style', 'font'].includes(type)
      },
    },
  },

  hooks: {
    'vue-renderer:ssr:context'(context) {
      const routePath = JSON.stringify(context.nuxt.routePath)
      context.nuxt = { serverRendered: true, routePath }
    },
  },

  head: {
    title: 'Глоссарий терминов',
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' },
      {
        rel: 'prefetch',
        type: 'image/svg+xml',
        href: '/static/sprites/main.svg',
        as: 'image',
      },
    ],
  },

  css: ['~/assets/scss/bootstrap.scss', '~/assets/scss/global.scss'],

  plugins: [
    { src: '~/plugins/axios', ssr: true },
    { src: '~/plugins/headMixin', ssr: true },
    { src: '~/plugins/localStorage', ssr: false },
    { src: '~/plugins/injections', ssr: false },
    { src: '~/plugins/init', ssr: false },
    { src: '~/plugins/lazySizes', ssr: true },
    { src: '~/plugins/globalComponents', ssr: true },
  ],

  components: false,

  buildModules: ['@nuxtjs/eslint-module'],

  modules: [
    '@nuxtjs/dotenv',
    'cookie-universal-nuxt',
    '@nuxtjs/svg-sprite',
    ['@nuxtjs/toast', toastConfig],
    '@nuxtjs/axios',
    ['bootstrap-vue/nuxt', bootstrapConfig],
    ['@nuxtjs/moment', momentConfig],
    'nuxt-vue-multiselect',
    // ['@nuxtjs/yandex-metrika', yandexMetrikaConfig],
  ],

  svgSprite: svgSpriteConfig,

  loading: { color: '#0163ff', throttle: 0 },

  build: {
    analyze: false, // True to get packages diagram
    extractCSS: process.env.NODE_ENV !== 'development',

    loaders: {
      vue: {
        compiler: require('vue-template-babel-compiler'),
      },
    },

    // Don't save cache in dev (for CloudFlare)
    devMiddleware: {
      headers: {
        'Cache-Control': 'no-store',
        Vary: '*',
      },
    },

    '@fullhuman/postcss-purgecss': {
      content: [
        './pages/**/*.vue',
        './layouts/**/*.vue',
        './components/**/*.vue',
        './content/**/*.md',
        './content/**/*.json',
      ],
      whitelist: [
        'html',
        'body',
        'has-navbar-fixed-top',
        'nuxt-link-exact-active',
        'nuxt-progress',
      ],
      whitelistPatternsChildren: [/svg-inline--fa/, /__layout/, /__nuxt/],
    },

    extend(config, { isDev, isClient, loaders: { vue } }) {
      // Image lazy load
      if (isClient) {
        vue.transformAssetUrls.img = ['data-src', 'src']
        vue.transformAssetUrls.source = ['data-srcset', 'srcset']
      }

      // ESLint tests
      if (isDev) {
        config.resolve.symlinks = false
      }

      if (isDev && isClient) {
        config.module.rules.push({
          enforce: 'pre',
          test: /\.(js|vue)$/,
          loader: 'eslint-loader',
          exclude: /(node_modules)/,
        })
      }
    },
  },
}
