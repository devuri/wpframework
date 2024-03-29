import{_ as s,c as i,o as a,a4 as e}from"./chunks/framework.ZNwHIQsX.js";const g=JSON.parse('{"title":"Premium Plugin Installation Guide","description":"","frontmatter":{},"headers":[],"relativePath":"premium-plugins.md","filePath":"premium-plugins.md"}'),n={name:"premium-plugins.md"},t=e(`<h1 id="premium-plugin-installation-guide" tabindex="-1">Premium Plugin Installation Guide <a class="header-anchor" href="#premium-plugin-installation-guide" aria-label="Permalink to &quot;Premium Plugin Installation Guide&quot;">​</a></h1><p>This comprehensive guide explains how to install premium plugins via Composer. In this example, we will cover the installation process for three premium plugins: ACF PRO, Elementor, and Object Cache Pro.</p><h2 id="advantages-of-installing-premium-plugins-via-composer" tabindex="-1">Advantages of Installing Premium Plugins via Composer <a class="header-anchor" href="#advantages-of-installing-premium-plugins-via-composer" aria-label="Permalink to &quot;Advantages of Installing Premium Plugins via Composer&quot;">​</a></h2><p>Installing premium plugins via Composer offers several benefits, which include:</p><ol><li><p><strong>Automation and Version Control</strong>: Composer simplifies the process of managing dependencies by automating the installation, updating, and removal of plugins. This ensures that you are using the correct version of each plugin, reducing compatibility issues and potential conflicts with other WordPress components.</p></li><li><p><strong>Consistency Across Environments</strong>: Composer installations are consistent across development, staging, and production environments. This consistency minimizes the risk of errors that may arise from manually uploading files or using different installation methods in various environments.</p></li><li><p><strong>Security</strong>: Authentication and private repositories provide a secure way to access premium plugins. Your credentials are stored securely, and you have control over access, reducing the risk of unauthorized use.</p></li><li><p><strong>Ease of Deployment</strong>: When using Composer, deploying your WordPress site to a new server or environment becomes straightforward. You can replicate your setup by running <code>composer install</code>, which downloads all required plugins, making the deployment process faster and more reliable.</p></li><li><p><strong>Centralized Management</strong>: All your plugins, including premium ones, are managed through Composer, making it easier to keep your WordPress site up to date with the latest versions. You can update all dependencies with a single command.</p></li></ol><h2 id="step-1-configure-authentication" tabindex="-1">Step 1: Configure Authentication <a class="header-anchor" href="#step-1-configure-authentication" aria-label="Permalink to &quot;Step 1: Configure Authentication&quot;">​</a></h2><p>Before installing premium plugins, it&#39;s essential to set up authentication in your Composer configuration. This ensures that you have the necessary credentials to access the plugin repositories. Here&#39;s how to do it:</p><p><strong>1. Create or Edit <code>auth.json</code></strong></p><p>You should have an <code>auth.json</code> file in your Composer global configuration directory. If it doesn&#39;t exist, create it. Add the authentication details for ACF PRO, Elementor, and Object Cache Pro as shown below:</p><div class="language-shell vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">shell</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">{</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">  &quot;http-basic&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> {</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">    &quot;connect.advancedcustomfields.com&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> {</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;username&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;Dvl2P9fLovYy2oJkdYOPiCrHXcRgGrmk9WR62HdErPasPsV43COx0anwTizc9XFrY8qysqqZ&quot;,</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;password&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;https://mysite.com&quot;</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    },</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">    &quot;composer.elementor.com&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> {</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;username&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;token&quot;,</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;password&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;&lt;elementor-license-key&gt;&quot;</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    },</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">    &quot;objectcache.pro&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> {</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;username&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;token&quot;,</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;password&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;&lt;object-cache-license-key&gt;&quot;</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    }</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">  }</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">}</span></span></code></pre></div><p>Replace <code>&lt;elementor-license-key&gt;</code> and <code>&lt;object-cache-license-key&gt;</code> with your actual Elementor and Object Cache Pro license keys.</p><p>Alternatively, you can set authentication through the <code>COMPOSER_AUTH</code> environment variable for added flexibility.</p><h2 id="step-2-add-repositories" tabindex="-1">Step 2: Add Repositories <a class="header-anchor" href="#step-2-add-repositories" aria-label="Permalink to &quot;Step 2: Add Repositories&quot;">​</a></h2><p>Next, add the repositories for the premium plugins to your <code>composer.json</code> file. This tells Composer where to find the plugins. Here&#39;s how to do it:</p><p><strong>1. Edit <code>composer.json</code></strong></p><p>In your project&#39;s <code>composer.json</code> file, add the repositories for ACF PRO, Elementor, and Object Cache Pro as follows:</p><div class="language-shell vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">shell</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">{</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">  &quot;repositories&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;"> [</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    {</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;type&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;composer&quot;,</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;url&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;https://composer.elementor.com&quot;,</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;only&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;"> [</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">        &quot;elementor/elementor-pro&quot;</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">      ]</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    },</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    {</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;type&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;composer&quot;,</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;url&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;https://connect.advancedcustomfields.com&quot;</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    },</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    {</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;type&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;composer&quot;,</span></span>
<span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">      &quot;url&quot;</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">:</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> &quot;https://objectcache.pro/repo/&quot;</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">    }</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">  ]</span></span>
<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">}</span></span></code></pre></div><h2 id="step-3-install-the-plugins" tabindex="-1">Step 3: Install the Plugins <a class="header-anchor" href="#step-3-install-the-plugins" aria-label="Permalink to &quot;Step 3: Install the Plugins&quot;">​</a></h2><p>With authentication and repositories set up, you can now install the premium plugins using Composer. For example, to install ACF PRO, Elementor, and Object Cache Pro, run the following commands:</p><p>For ACF PRO:</p><div class="language-shell vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">shell</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">composer</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> require</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> wpengine/advanced-custom-fields-pro</span></span></code></pre></div><p>For Elementor:</p><div class="language-shell vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">shell</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">composer</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> require</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> elementor/elementor-pro</span></span></code></pre></div><p>For Object Cache Pro:</p><div class="language-shell vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">shell</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">composer</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> require</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> rhubarbgroup/object-cache-pro</span></span></code></pre></div><h2 id="additional-information" tabindex="-1">Additional Information <a class="header-anchor" href="#additional-information" aria-label="Permalink to &quot;Additional Information&quot;">​</a></h2><p>For more detailed installation instructions and resources for these premium plugins, please visit their respective websites:</p><ul><li><a href="https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/" target="_blank" rel="noreferrer">ACF PRO Installation with Composer</a></li><li><a href="https://developers.elementor.com/docs/cli/composer/" target="_blank" rel="noreferrer">Elementor Composer Integration</a></li><li><a href="https://objectcache.pro/docs/installation/" target="_blank" rel="noreferrer">Object Cache Pro Installation</a></li></ul>`,28),l=[t];function o(p,r,h,d,k,c){return a(),i("div",null,l)}const m=s(n,[["render",o]]);export{g as __pageData,m as default};
