$(document).ready(function () {
    $('.nav-link.active .sub-menu').slideDown();
    $('.nav-link.active .arrow').toggleClass('fa-angle-right fa-angle-down');
    $('#sidebar-menu .arrow').click(function () {
        $(this).parents('li').children('.sub-menu').slideToggle();
        $(this).toggleClass('fa-angle-right fa-angle-down');
    });



    $("input[name='checkall']").click(function () {
        var checked = $(this).is(':checked');
        $('.table-checkall tbody tr td input:checkbox').prop('checked', checked);
    });


    // add thumb-nail event
    $("#image").change(function () {
        var input = this;
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#uploadedImage").attr("src", e.target.result);
                $("#uploadedImage").css({
                    'display': 'block',
                    'height': '150px'
                });
            };
            reader.readAsDataURL(input.files[0]);
        }
    });

    // add media for product

    let uploads = {};
    let uploadedFiles = []; // List of uploaded files
    let fileIndex = 0;

    $("#media").change(function () {
        let input = this;

        if (input.files && input.files.length > 0) {
            for (let i = 0; i < input.files.length; i++) {
                let currentIndex = fileIndex++;
                let file = input.files[i];
                // If the file already exists, skip it.
                if ($(`.preview-block[data-name="${file.name}"]`).length > 0) {
                    continue;
                }

                let previewHtml = '';

                if (file.type.startsWith("image/")) {
                    previewHtml = `<img>`;
                    appendPreview(previewHtml, currentIndex, file, "img");

                    let reader = new FileReader();
                    reader.onload = function (e) {
                        $(`.preview-block[data-index='${currentIndex}'] img`).attr("src", e.target.result);
                    };
                    reader.readAsDataURL(file);
                } else if (file.type.startsWith("video/")) {
                    let videoURL = URL.createObjectURL(file);
                    previewHtml = `<video controls>
                                <source src="${videoURL}" type="${file.type}">
                               </video>`;
                    appendPreview(previewHtml, currentIndex, file, "video");
                }

                // Upload Ajax
                startUpload(file, currentIndex);
            }
            $(this).val(""); // Reset input
        }
    });

    // create preview block
    function appendPreview(previewHtml, index, file, type, isRestored = false) {
        let spinnerHtml = isRestored ? "" : `<div class="loading-spinner"><div class="spinner"></div></div>`;
        let previewBlock = $(`
        <div class="preview-block" data-index="${index}" data-name="${file.name}">
            <button class="remove-btn">&times;</button>
            ${previewHtml}
            ${spinnerHtml}
            <div class="progress-container">
                <div class="progress-bar" data-index="${index}"></div>
            </div>
        </div>
    `);

        // delete file when click the button ❌
        previewBlock.find(".remove-btn").on("click", function () {
            if (uploads[index]) {
                uploads[index].abort();
                delete uploads[index];
            }

            // Take path from uploadedFiles
            let fileData = uploadedFiles.find(f => f.index === index);
            if (!fileData) return;

            // If the file is in public/media → mark to delete
            if (fileData.path.startsWith('media/')) {
                fileData.deleted = true; // backend will handle
            } else {
                // file tmp → delete by ajax
                if (uploads[index]) uploads[index].abort();
                $.post(deleteMedia, {
                    _token: $("meta[name='csrf-token']").attr("content"),
                    path: fileData.path
                });
                fileData.deleted = true; // backend sẽ xử lý
            }

            // Remove from uploaded file list
            uploadedFiles = uploadedFiles.filter(f => f.index !== index);
            $("#uploaded_media").val(JSON.stringify(uploadedFiles));
            previewBlock.remove();
        });

        $(".product_media .media").append(previewBlock);
    }

    // upload function
    function startUpload(file, index) {
        let formData = new FormData();
        formData.append("media", file);
        formData.append("_token", $("meta[name='csrf-token']").attr("content"));

        let xhr = $.ajax({
            url: uploadMedia, // Route Laravel
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            // show progress bar
            xhr: function () {
                let myXhr = new window.XMLHttpRequest();
                myXhr.upload.addEventListener("progress", function (evt) {
                    if (evt.lengthComputable) {
                        let percent = (evt.loaded / evt.total) * 100;
                        $(`.progress-bar[data-index='${index}']`).css("width", percent + "%");
                    }
                }, false);
                return myXhr;
            },
            success: function (response) {
                if (response.error) {
                    // show error
                    let block = $(`.preview-block[data-index='${index}']`);
                    block.find(".loading-spinner").remove();
                    block.append(`<div class="error-message" style="color:red; margin-top:5px;">${response.error}</div>`);
                    return;
                }

                // Save uploaded file path to hidden input
                uploadedFiles.push({ index: index, path: response.path, new: true });
                $("#uploaded_media").val(JSON.stringify(uploadedFiles));

                // show uploaded success
                let block = $(`.preview-block[data-index='${index}']`);
                block.find(".loading-spinner").fadeOut();
                // block.find(".loading-spinner").remove();

            },
            error: function (xhr) {
                let msg = "Upload fail!";
                if (xhr.status === 413) {
                    msg = "File too large! >512mb";
                }
                let block = $(`.preview-block[data-index='${index}']`);
                block.find(".loading-spinner").remove();
                block.append(`<div class="error-message" style="color:red; margin-top:5px; font-size:12px">${msg}</div>`);

            }
        });

        uploads[index] = xhr;
    }

    $('.btn_upload').click(function () {
        $(this).next('input').click();
    });


    $(document).ready(function () {

        // When reloading the page, if there is old uploaded_media then re-render
        if ($("#uploaded_media").val()) {
            try {
                let oldMedia = JSON.parse($("#uploaded_media").val());
                uploadedFiles = []; // reset before push
                let maxIndex = -1; // save highest index
                let baseUrl = '/crystal_remake/public/';

                oldMedia.forEach(function (fileData, index) {
                    let previewHtml = "";
                    let src;

                    if (fileData.path.startsWith('media/')) {
                        src = baseUrl + fileData.path; // prepend correct path
                    } else {
                        src = baseUrl + '/storage/' + fileData.path; // New file upload (add product)
                    }

                    if (fileData.path.match(/\.(jpeg|jpg|png|gif|webp)$/i)) {
                        previewHtml = `<img src="${src}">`;
                        appendPreview(previewHtml, index, { name: fileData.path }, "img", true);
                    } else if (fileData.path.match(/\.(mp4|webm|ogg)$/i)) {
                        previewHtml = `<video controls>
                        <source src="${src}">
                       </video>`;
                        appendPreview(previewHtml, index, { name: fileData.path }, "video", true);
                    }

                    uploadedFiles.push({ index: index, path: fileData.path });
                    if (index > maxIndex) maxIndex = index;
                });
                fileIndex = maxIndex + 1;
                $("#uploaded_media").val(JSON.stringify(uploadedFiles));
            } catch (e) {
                console.error("Parse uploaded_media error:", e);
            }
        }

    });

    //add product category field event


    function updateParentCheckbox() {
        $('.cat-child input[type="checkbox"]').change(function () {
            var parentCatId = $(this).closest('.cat-child').attr('data-parent');
            var parentCheckbox = $('.cat-parent[data-id="' + parentCatId +
                '"] input[type="checkbox"]');
            if ($(this).is(':checked')) {
                parentCheckbox.prop('checked', true);
            } else {
                var anyChildChecked = $('.cat-child[data-parent="' + parentCatId +
                    '"] input[type="checkbox"]:checked').length > 0;
                if (!anyChildChecked) {
                    parentCheckbox.prop('checked', false);
                }
            }
        });
    }

    $("#add_product_category").change(function () {
        $('.list_subcategories').empty();
        var category = $(this).val();
        var data = {
            category: category
        };
        $.ajax({
            url: ajaxUrl,
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                $('.list_subcategories .form-checkbox').remove();
                var list_subcategories = data;
                if (list_subcategories.length > 0) {
                    $('.sidebar_title').css('display', 'block');
                } else {
                    $('.sidebar_title').css('display', 'none');
                }
                $.each(list_subcategories, function (index, item) {
                    var formCheckboxDiv = $('<div>', {
                        class: 'form-checkbox ' + (item.level === 0 ?
                            'cat-parent' : 'cat-child'),
                        'data-id': item.level == 0 ? item
                            .id : undefined,
                        'data-parent': item.level !== 0 ? item
                            .parent_id : undefined
                    }).css('display', 'flex');
                    if (item.level == 0) {
                        formCheckboxDiv.css({
                            'width': '100%',
                            'margin-bottom': '10px'
                        });
                    } else {
                        formCheckboxDiv.css('margin-bottom', '15px');
                    }
                    var inputCheckbox = $('<input>', {
                        type: 'checkbox',
                        name: 'sidebar[]',
                        value: item.id,
                        id: 'sidebar-' + item.id,
                    });
                    // Keep the checked state if the user has previously selected it
                    if (oldSidebar.includes(String(item.id))) {
                        inputCheckbox.prop('checked', true);
                    }
                    var label = $('<label>', {
                        for: 'sidebar-' + item.id,
                        text: item.title
                    }).css({
                        'padding-bottom': '0',
                        'padding-left': '5px',
                        'margin-right': '30px',
                        'margin-bottom': '0px'
                    });
                    if (item.level == 0) {
                        label.css({
                            'text-transform': 'uppercase',
                            'font-weight': '500'
                        });
                    }
                    formCheckboxDiv.append(inputCheckbox);
                    formCheckboxDiv.append(label);
                    $('.list_subcategories').append(formCheckboxDiv);
                });
                updateParentCheckbox();
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })
    // updateParentCheckbox();
    $(document).ready(function () {
        // If there is an oldCategory, reload the subcategory when the page is reloaded
        if (typeof oldCategory !== "undefined" && oldCategory) {
            $("#add_product_category").val(oldCategory).trigger("change");
        }
    });
    //add sidebar ajax event
    $("#category").change(function () {
        var category_id = $(this).val();
        var subcategory = $("#subcategory").val();
        var edit_value = $("#edit_value").val();        //get sidebar_id when edit
        var data = {
            category_id: category_id,
            subcategory: subcategory,
            edit_value: edit_value
        };
        $.ajax({
            url: "../ajax",
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                //dump subcategory data by category
                if (data['list_subcategory']) {
                    var subSelect = $("#subcategory");
                    subSelect.empty();
                    subSelect.append('<option value="">Select parent subcategory</option>');
                    if (data['subcategory'] == 0) {
                        subSelect.append('<option value="0" selected>Set as parent subcategory</option>');
                    } else {
                        subSelect.append('<option value="0">Set as parent subcategory</option>');
                    }
                    data['list_subcategory'].forEach(function (item) {
                        subSelect.append('<option value="' + item.id + '">' + item.title + '</option>');
                    });
                } else {
                    return;
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })
    $("#subcategory").change(function () {
        var subcategory_id = $(this).val();
        var category_id = $('#category').val()
        var data = {
            subcategory_id: subcategory_id
        };
        $.ajax({
            url: "../ajax",
            method: 'GET',
            data: data,
            dataType: 'json',
            success: function (data) {
                //select subcategory, the main category of this subcategory will seclect
                if (data) {
                    $("#category").val(data.id);
                } else {
                    $("#category").val('');
                }
                if (subcategory_id == 0) {
                    $("#category").val(category_id);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(xhr, ajaxOptions);
                alert(thrownError);
            }
        })
    })
});

