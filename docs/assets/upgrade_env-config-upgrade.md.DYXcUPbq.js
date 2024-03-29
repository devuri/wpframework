import{_ as e,c as a,o as i,a4 as s}from"./chunks/framework.CPs9Ukbn.js";const g=JSON.parse('{"title":"Upgrade ^0.0.5","description":"","frontmatter":{},"headers":[],"relativePath":"upgrade/env-config-upgrade.md","filePath":"upgrade/env-config-upgrade.md"}'),o={name:"upgrade/env-config-upgrade.md"},t=s('<h1 id="upgrade-0-0-5" tabindex="-1">Upgrade ^0.0.5 <a class="header-anchor" href="#upgrade-0-0-5" aria-label="Permalink to &quot;Upgrade ^0.0.5&quot;">​</a></h1><blockquote><p>Upgrade from wp-env-config to wpframework version 5</p></blockquote><p>To upgrade your project to use the latest <code>wpframework</code> version 5 and integrate the new initialization process in your <code>bootstrap.php</code> file, follow this guide:</p><h3 id="_1-update-composer-json" tabindex="-1">1. Update <code>composer.json</code> <a class="header-anchor" href="#_1-update-composer-json" aria-label="Permalink to &quot;1. Update `composer.json`&quot;">​</a></h3><p>Begin by updating your project&#39;s <code>composer.json</code> to require version 5 of <code>wpframework</code>:</p><ol><li><p>Open <code>composer.json</code> in your project&#39;s root directory.</p></li><li><p>Locate the <code>require</code> section and replace the entry for <code>devuri/wp-env-config</code> with <code>devuri/wpframework</code>, specifying version 5:</p><div class="language-json vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">json</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;">&quot;require&quot;</span><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">: {</span></span>\n<span class="line"><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">    &quot;devuri/wpframework&quot;</span><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">: </span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;">&quot;^0.0.5&quot;</span></span>\n<span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">}</span></span></code></pre></div></li><li><p>Save your changes.</p></li></ol><h3 id="_2-update-project-dependencies" tabindex="-1">2. Update Project Dependencies <a class="header-anchor" href="#_2-update-project-dependencies" aria-label="Permalink to &quot;2. Update Project Dependencies&quot;">​</a></h3><p>Run Composer to update your dependencies, which will remove the old <code>wp-env-config</code> package and install the specified version of <code>wpframework</code>:</p><div class="language-bash vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">bash</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">composer</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> update</span></span></code></pre></div><h3 id="_3-remove-the-old-mu-plugin" tabindex="-1">3. Remove the Old MU-Plugin <a class="header-anchor" href="#_3-remove-the-old-mu-plugin" aria-label="Permalink to &quot;3. Remove the Old MU-Plugin&quot;">​</a></h3><p>Remove the <code>compose.php</code> file from your <code>mu-plugins</code> directory, as it will be replaced with the new <code>wpframework.php</code> file:</p><div class="language-bash vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">bash</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">rm</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> mu-plugins/compose.php</span></span></code></pre></div><h3 id="_4-download-the-new-mu-plugin-file" tabindex="-1">4. Download the New MU-Plugin File <a class="header-anchor" href="#_4-download-the-new-mu-plugin-file" aria-label="Permalink to &quot;4. Download the New MU-Plugin File&quot;">​</a></h3><p>Download the new <code>wpframework.php</code> file from the <code>wpframework</code> GitHub repository and place it in your <code>mu-plugins</code> directory. You can do this manually or use the following command:</p><p>Using <code>wget</code>:</p><div class="language-bash vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">bash</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">wget</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> https://raw.githubusercontent.com/devuri/wpframework/master/src/inc/mu-plugin/wpframework.php</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;"> -O</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> mu-plugins/wpframework.php</span></span></code></pre></div><p>Or using <code>curl</code>:</p><div class="language-bash vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">bash</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;">curl</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;"> -o</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> mu-plugins/wpframework.php</span><span style="--shiki-light:#032F62;--shiki-dark:#9ECBFF;"> https://raw.githubusercontent.com/devuri/wpframework/master/src/inc/mu-plugin/wpframework.php</span></span></code></pre></div><h3 id="_5-update-the-bootstrap-php-file" tabindex="-1">5. Update the <code>bootstrap.php</code> File <a class="header-anchor" href="#_5-update-the-bootstrap-php-file" aria-label="Permalink to &quot;5. Update the `bootstrap.php` File&quot;">​</a></h3><p>In your <code>bootstrap.php</code> file, update the application initialization line to use <code>wpframework</code>:</p><div class="language-php vp-adaptive-theme"><button title="Copy Code" class="copy"></button><span class="lang">php</span><pre class="shiki shiki-themes github-light github-dark vp-code"><code><span class="line"><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">$http_app </span><span style="--shiki-light:#D73A49;--shiki-dark:#F97583;">=</span><span style="--shiki-light:#6F42C1;--shiki-dark:#B392F0;"> wpframework</span><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">(</span><span style="--shiki-light:#005CC5;--shiki-dark:#79B8FF;">__DIR__</span><span style="--shiki-light:#24292E;--shiki-dark:#E1E4E8;">);</span></span></code></pre></div><p>This line initializes your application with the base directory as its context, leveraging the <code>wpframework</code>&#39;s functionalities.</p><h3 id="_6-test-your-application" tabindex="-1">6. Test Your Application <a class="header-anchor" href="#_6-test-your-application" aria-label="Permalink to &quot;6. Test Your Application&quot;">​</a></h3><p>After making all the updates, thoroughly test your application to ensure everything functions correctly. Verify that the <code>wpframework</code> is properly initialized and all configurations are loaded as expected.</p><p>By following these steps, your project will be successfully updated to use version 5 of the <code>wpframework</code>, with the old <code>wp-env-config</code> removed and replaced by the new initialization process in the <code>bootstrap.php</code> file.</p>',25),p=[t];function n(r,l,d,h,c,u){return i(),a("div",null,p)}const m=e(o,[["render",n]]);export{g as __pageData,m as default};
