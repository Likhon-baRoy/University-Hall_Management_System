(function ($) {
    $(document).ready(function () {
        // Check if DataTables is loaded before initializing
        if ($.fn.DataTable) {
            $('.data-table-element').DataTable();
        } else {
            console.error("DataTables plugin is not loaded.");
        }

        // // Delete button confirmation with SweetAlert
        // $(document).on('submit', '.delete-form', function(e) {
        //     e.preventDefault();
        //     const form = $(this);

        // //     Swal.fire({
        // //         title: 'Are you sure?',
        // //         text: "This will permanently delete the application. This action cannot be undone!",
        // //         icon: 'warning',
        // //         showCancelButton: true,
        // //         confirmButtonColor: '#d33',
        // //         cancelButtonColor: '#3085d6',
        // //         confirmButtonText: 'Yes, delete it!',
        // //         cancelButtonText: 'Cancel'
        // //     }).then((result) => {
        // //         if (result.isConfirmed) {
        // //             form.off('submit').submit();
        // //         }
        // //     });
        // // });

        // Photo preview management
        $('#photo-preview').change(function (e) {
            const photo_url = URL.createObjectURL(e.target.files[0]);
            $('#make-photo-preview').attr('src', photo_url);
        });

        // Add new slider button
        let btn_no = 1;
        $('#add-new-slider-button').click(function (e) {
            e.preventDefault();
            $('.slider-btn-opt').append(`
                <div class="btn-opt-area">
                    <span>Button #${btn_no}</span>
                    <span class="badge badge-danger remove-btn" style="margin-left:300px;cursor:pointer;">remove</span>
                    <input class="form-control" name="btn_title[]" type="text" placeholder="Button Title">
                    <input class="form-control" name="btn_link[]" type="text" placeholder="Button Link">
                    <select class="form-control" name="btn_type[]">
                        <option value="btn-light-out">default</option>
                        <option value="btn-color btn-full">Red</option>
                    </select>
                    <hr />
                </div>
            `);
            btn_no++;
        });

        // Remove button
        $(document).on('click', '.remove-btn', function () {
            $(this).closest('.btn-opt-area').remove();
        });

        // Icon select modal
        $('button.show-icon').click(function (e) {
            e.preventDefault();
            $('#select-icon').modal('show');
        });

        // Select icon
        $('.select-icon-haq .preview-icon code').click(function () {
            let icon_name = $(this).html();
            $('.select-haq-icon-input').val(icon_name);
            $('#select-icon').modal('hide');
        });

        // Append SVG icons to submenu items
        function getCommitNodeIcon() {
            const template = document.getElementById('commit-node-icon');
            return template.content.cloneNode(true);
        }

        $('.submenu li a .icon').each(function () {
            $(this).append(getCommitNodeIcon());
        });
    });
})(jQuery);
