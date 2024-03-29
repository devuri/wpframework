import { defineConfig } from 'vitepress'

// https://vitepress.dev/reference/site-config
export default defineConfig({
  logo: '/logo.png',
  lang: 'en-US',
  title: 'Raydium',
  description: 'Effortlessly develop scalable WordPress applications that support multiple tenants from a single installation.',
  srcDir: 'src',
  themeConfig: {
    // https://vitepress.dev/reference/default-theme-config
    nav: [
      { text: 'Home', link: '/' },
      { text: 'Guide', link: '/guide/what-is-raydium' },
      { text: 'Reference', link: '/reference/app-configs' }
    ],

    sidebar: [
		{
		  text: 'Quick Start',
		  collapsible: true,
		  collapsed: false,
		  items: [
			{ text: 'Why Raydium', link: '/guide/why-raydium' },
			{ text: 'What Is Raydium', link: '/guide/what-is-raydium' },
			{ text: 'Getting Started', link: '/guide/getting-started' },
			{ text: 'Installation', link: '/guide/installation' },
			{ text: 'Migration', link: '/guide/migration' },
			{ text: 'Deploy', link: '/guide/deploy' }
		  ]
		},
		{
		  text: 'Customization',
		  collapsible: true,
		  collapsed: true,
		  items: [
			{ text: 'Configs', link: '/customization/app-config' },
			{ text: 'Environment', link: '/customization/environment-variables' }
		  ]
		},
		{
		  text: 'Reference',
		  collapsible: true,
		  collapsed: true,
		  items: [
			  { text: 'Configs', link: '/customization/configuration' },
  			  { text: 'Environment', link: '/customization/environment-variables' },
  			  { text: 'code', link: '/docs/code' }
		  ]
		},
      {
        text: 'Advanced',
        items: [
          { text: 'Premium Plugins', link: '/premium-plugins' },
          { text: 'Environment Variables', link: '/environment-variables' },
          { text: 'Multi-Tenant', link: '/multi-tenant' }
        ]
      }
    ],
    socialLinks: [
	      { icon: 'github', link: 'https://github.com/devuri/wpframework' }
	],
	search: {
	      provider: 'local'
	  },
	footer: {
	      message: 'Released under the <a href="https://github.com/devuri/wpframework/blob/master/LICENSE">MIT License</a>.',
	      copyright: 'Copyright © <a href="https://github.com/devuri">Uriel Wilson</a>'
	}
  }


})
