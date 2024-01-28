<script>
    import hljs from 'highlight.js';
	import { onMount } from 'svelte';
	import { page} from '$app/stores';
    import Prism from 'prismjs';
    import 'prismjs/components/';

	/**
     * @type {string}
	 */
	let id = $page.params.id;

	/**
     * @type string
	 */
	let code;
    /**
     *  @type {any}
     */
    let lang;

	onMount(async _ =>
    {
		await fetch(`http://localhost:8080/api/paste/${id}`).then(res => res.json().then(json =>
        {
			console.log(json)
            code = hljs.highlight(json["language"], json["data"]).value;
            // code = json["data"]
            lang = json["language"];
            console.log(code)
        }));
    })

</script>

<style>
  @import 'prismjs/themes/prism.css';

  /* Add any additional styling here */
</style>


<pre><code class={lang}>{@html code}</code></pre>
<!--<pre>-->
<!--  <code>{@html Prism.highlight(code, Prism.languages[lang], lang)}</code>-->
<!--</pre>-->
