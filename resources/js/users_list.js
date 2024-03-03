$(document).ready(function () {
    $('.delete-user').on('click', function(e) {
        e.preventDefault();
        let id = $(this).data('id');
        const confirmDelete = window.confirm(`Are you sure you want to delete ${id}'s record?`)

        if (confirmDelete) {
            $.ajax({
                url: `/users/${id}/delete`,
                dataType: 'json',
                success: function (res) {
                    if (res.success) {
                        alert(`Deleted records of murid ${id} successfully!`);
                    } else {
                        alert('Internal error occurred, unable to delete!');
                    }
                },
                error: function (e) {
                    alert(`Failed to delete records of murid ${id}`);
                    console.error(e);
                }
            }).then(function () {
                location.reload();
            })
        }
    });
})