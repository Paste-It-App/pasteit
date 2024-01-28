<script>
    /**
     * @type {string}
     */
     export let id;
    /**
     * @type {string}
     */
     export let val;
     /**
     * @type {string}
     */
      export let labelName;
</script>


<style>
    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        display: block;
    }

    .form-group input[type="text"],
    .form-group input[type="email"],
    .form-group input[type="password"] {
        width: calc(100% - 22px);
        padding: 10px;
        margin: 8px 0;
        border: 1px solid #ccc;
        border-radius: 3px;
    }
</style>


<div class="form-group">
    <label for={id}>{labelName}:</label>
    <input type="password" id={id} bind:value={val} required>
</div>