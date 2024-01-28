<script>

	import NewPaste from './+page.svelte';
	import SeePaste from './[id]/+page.svelte'

	export const routes = {
		"/": NewPaste,
        "/:id": SeePaste
	}

</script>
