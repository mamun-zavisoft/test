// delete confirmation start

$(".confirm-text2").on("click", function (e) {
    e.preventDefault();

    // Get the associated delete form
    var deleteForm = $(this).closest("tr").find(".delete-form");

    // Show the SweetAlert confirmation dialog
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        // icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
    }).then((result) => {
        if (result.isConfirmed) {
            deleteForm.submit();
        }
    });
});

// delete confirmation end

// image preview start

$(".image-input").change(function () {
    const file = this.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            $(this)
                .closest(".profile-pic-upload")
                .find(".image-preview")
                .attr("src", e.target.result);
            $(this)
                .closest(".profile-pic-upload")
                .find(".remove-photo")
                .removeClass("d-none");
        }.bind(this); // Bind `this` to access the input element in the callback
        reader.readAsDataURL(file);
    }
});
$(document).on("click", ".remove-photo", function () {
    $(this).closest(".profile-pic-upload").find(".image-preview").attr("src", defaultUploadImagePath);
    $(this).closest(".profile-pic-upload").find(".image-input").val("");
    $(this).addClass("d-none");
});

// image preview end


// copy text
$(document).ready(function () {
    $(document).on('click', '.copyable', function () {
        const textToCopy = $(this).text();
        navigator.clipboard.writeText(textToCopy)
            .then(() => {
                $(this).addClass('copied'); // Add 'copied' class
                setTimeout(() => {
                    $(this).removeClass('copied'); // Remove 'copied' class after a delay
                }, 1000); // 1 seconds delay
            })
            .catch(err => {
                console.error('Could not copy text: ', err);
            });
    });

    // Ensure that dynamically loaded elements are also included
    $(document).ajaxComplete(function () {
        $(document).on('click', '.copyable', function () {
            const textToCopy = $(this).text();
            navigator.clipboard.writeText(textToCopy)
                .then(() => {
                    $(this).addClass('copied'); // Add 'copied' class
                    setTimeout(() => {
                        $(this).removeClass('copied'); // Remove 'copied' class after a delay
                    }, 1000); // 1 seconds delay
                })
                .catch(err => {
                    console.error('Could not copy text: ', err);
                });
        });
    });
});

// copy text end