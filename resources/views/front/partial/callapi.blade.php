<script>
    async function CallAPI(route, data, action) {

        // console.log("Call Api data",route,"Call Api code", data);

        const response = await fetch(route, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                data: data,
                action: action
            })
        });
        return await response.json();  

    }
</script>