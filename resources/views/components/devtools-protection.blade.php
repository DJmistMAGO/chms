<script>
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });

    document.addEventListener('keydown', function(e) {
        const key = e.key.toLowerCase();

        if (
            e.key === 'F12' ||
            (e.ctrlKey && e.shiftKey && ['i', 'j', 'c'].includes(key)) ||
            (e.ctrlKey && key === 'u')
        ) {
            e.preventDefault();
            return false;
        }
    });
</script>
{{-- this prevcents inspecting elements and debugging hahahahaha :) MSMG --}}
