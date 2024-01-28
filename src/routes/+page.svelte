<script>
    import hljs from 'highlight.js';
    import 'highlight.js/styles/default.css';
    import InputText from "$lib/components/inputText.svelte"

    let formData = {
		codeSnippet: '',
        pasteName: '',
        tags: '',
        selectedLang: 'plaintext'
    }

	let url = '';

    // Get the list of supported languages from Highlight.js
    const supportedLangs = hljs.listLanguages();

    let highlightedCode = hljs.highlight(formData.selectedLang, formData.codeSnippet).value;

    function updateCodeHighlight()
    {
        let value = document.querySelector("#pasteContent").value;
        highlightedCode = hljs.highlight(formData.selectedLang, value).value;
    }

	function handleForm()
	{
		let data = new FormData();
		data.append("pasteContent", formData.codeSnippet)
        data.append("pasteName", formData.pasteName)
        data.append("language", formData.selectedLang)
        data.append("tags", formData.tags)

        fetch("http://localhost:8080/api/paste", {
			method: "post",
            body: data
        }).then(res => res.json().then(json =>
        {
			if (res.ok)
			{
                url = '/' + json.uniqueID;
			}
        }))
    }

	function goToPaste()
	{
		window.location.href = url;
    }
    
</script>

<style>
    div.container {
        width: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        padding: 0 5em;
    }

    div.container form {
        margin: 0 auto;
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        display: block;
    }

    .form-group select, .form-group textarea {
        width: calc(100% - 22px);
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 3px;
    }

    .btn {
        background-color: rgb(15, 189, 15);
        color: #fff;
        border: none;
        padding: 10px 15px;
        border-radius: 3px;
        cursor: pointer;
    }

</style>


<div class="container">
    <form action="#" on:submit|preventDefault={handleForm}>
        <div class="form-group">
            <label for="pasteContent">New Paste: </label>
            <textarea name="pasteContent" id="pasteContent" cols="100" rows="10" bind:value={formData.codeSnippet}
                      on:input={updateCodeHighlight} required></textarea>
        </div>
        <InputText id="pasteName" labelName="Name" bind:val={formData.pasteName} isRequired="true"/>
        <div class="form-group">
            <label for="lang">Language</label>
            <select bind:value={formData.selectedLang} on:change={updateCodeHighlight} id="language" name="language" required>
              {#each supportedLangs as language}
                <option value={language}>{language}</option>
              {/each}
            </select>
        </div>
        <InputText id="pasteTags" labelName="Tags" bind:val={formData.tags} isRequired="false"/>
        <input type="submit" class="btn">
    </form>
</div>
<pre><code>{@html highlightedCode}</code></pre>
<div id="url">
    <button on:click|preventDefault={goToPaste}>See your paste</button>
</div>