let pageAPI, menuAPI;

pageAPI = {
    create: {
        document: function (pageName, file, onSuccess) {
            // e.originalEvent.dataTransfer.files[0]
            let formData = new FormData();
            formData.append('file', file);
            formData.append('type',"document");
            formData.append('pageName', pageName);
            formData.append('operation',"create");
            $.ajax({
                url: 'page_api.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: false,
                success: function(response){
                    onSuccess(response);
                }
            });
        },
        html: function (pageName, htmlData, onSuccess) {
            let formData = new FormData();
            formData.append('contentHtml', htmlData);
            formData.append('type',"html");
            formData.append('pageName', pageName);
            formData.append('operation',"create");
            $.ajax({
                url: 'page_api.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: false,
                success: function(response){
                    onSuccess(response);
                }
            });
        }
    },
    update: {
        document: function (id, pageName, file, onSuccess) {
            // e.originalEvent.dataTransfer.files[0]
            let formData = new FormData();
            formData.append('file', file);
            formData.append('type',"document");
            formData.append('pageName', pageName);
            formData.append('id', id);
            formData.append('operation',"update");
            $.ajax({
                url: 'page_api.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: false,
                success: function(response){
                    onSuccess(response);
                }
            });
        },
        html: function (id, pageName, htmlData, onSuccess) {
            let formData = new FormData();
            formData.append('contentHtml', htmlData);
            formData.append('type',"html");
            formData.append('pageName', pageName);
            formData.append('id', id);
            formData.append('operation',"update");
            $.ajax({
                url: 'page_api.php',
                type: 'post',
                data: formData,
                contentType: false,
                processData: false,
                dataType: false,
                success: function(response){
                    onSuccess(response);
                }
            });
        }
    },
    delete: function (id, onSuccess) {
        let formData = new FormData();
        formData.append('id', id);
        formData.append('operation',"delete");
        $.ajax({
            url: 'page_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    },
    get: function (id, onSuccess) {
        let formData = new FormData();
        formData.append('id', id);
        formData.append('operation',"get");
        $.ajax({
            url: 'page_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    },
    list: function (onSuccess) {
        let formData = new FormData();
        formData.append('operation',"list");
        $.ajax({
            url: 'page_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    }
};

menuAPI = {
    updateStructure: function(jsonData, onSuccess) {
        let formData = new FormData();
        formData.append('data', jsonData);
        formData.append('operation',"updateStructure");
        $.ajax({
            url: 'menu_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    },
    getStructure: function(onSuccess) {
        let formData = new FormData();
        formData.append('operation',"getStructure");
        $.ajax({
            url: 'menu_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    },
    regenerate: function(onSuccess) {
        let formData = new FormData();
        formData.append('operation',"regenerate");
        $.ajax({
            url: 'menu_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    }
};

PluginAPI = {
    basic: function(data, onSuccess, plugin, action) {
        let formData = new FormData();
        if(data !== null)formData.append('data', data);
        formData.append('type',"basic");
        formData.append('plugin', plugin);
        formData.append('action', action);
        $.ajax({
            url: 'ncld_plugin_manager/auto_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    },
    file: function(file, data, onSuccess, plugin, action) {
        let formData = new FormData();
        if(data !== null)formData.append('data', data);
        formData.append('type',"file_upload");
        formData.append('plugin', plugin);
        formData.append('action', action);
        formData.append('file', file);
        $.ajax({
            url: 'ncld_plugin_manager/auto_api.php',
            type: 'post',
            data: formData,
            contentType: false,
            processData: false,
            dataType: false,
            success: function(response){
                onSuccess(response);
            }
        });
    },
    test: function () {
        openUploadDialog(function (file) {
            uploadDialogData.loading();
            $("#btn-upload-modal-ok").hide();
            if(file) PluginAPI.file(file, null, function (response) {
                uploadDialogData.success();
                console.log(response);
            }, "gdrive", "upload");
        },"NCLD")
    }
};

let currentMenuStruct = {};
let currentPagesArray = {};

let idCounter = 0;
let getUniqueID = function() {
    const id = `elem_${idCounter}`;
    idCounter++;
    return id;
};

let loadPages = function(callback) {

    pageAPI.list(function (response) {
       let pagesArray = JSON.parse(response);
       currentPagesArray = pagesArray;
       let i;
       $("#pageList").text("");
       $("#page-select-choice").text("");
       for(i of pagesArray) {
           const pageDeleteIconId = getUniqueID();
           const pageEditIconId = getUniqueID();
           const pagePreviewIconId = getUniqueID();
           const pageData = i;
           $("#pageList").append(`<li class="list-group-item">${i.id}) ${i.name}
                <i class="fas fa-trash" id="${pageDeleteIconId}" style="float: right;"></i> 
                <i class="fas fa-pencil-alt" id="${pageEditIconId}" style="float: right;margin-right: 15px;"></i>  
                <i class="fas fa-eye" id="${pagePreviewIconId}" style="float: right;margin-right: 15px;"></i> 
           </li>`);
           $("#page-select-choice").append(`<input type="radio" name="page" id="select_${pageDeleteIconId}" value="${i.id}"> ${i.id}) ${i.name}<br>`);
           $(`#${pageDeleteIconId}`).on('click', function () {
                openConfirmDialog("Ștergeți pagina "+ pageData.name +" din site?", function () {
                    pageAPI.delete(pageData.id, function () {
                        $(`#${pageDeleteIconId}`).parent().remove();
                        $(`#select_${pageDeleteIconId}`).remove();
                        loadPages(()=>{
                            getMenuArray();
                        });

                    });
                });
           });
           $(`#${pageEditIconId}`).on('click', function() {

               if(pageData.data.type === 'html') {

                   let formData = new FormData();
                   //formData.append("id", String(pageData.id));
                   $.ajax({
                       url: `static/file_${pageData.id}.html`,
                       type: 'get',
                       data: formData,
                       contentType: false,
                       processData: false,
                       dataType: false,
                       success: function(response){
                           htmlFieldEditDialog(function (html) {
                               pageAPI.update.html(pageData.id,pageData.name, html, function () {
                                    console.log("Am modificat pagina");
                               })
                           }, "Editează pagina", response);
                       }
                   });

               }
               else if(pageData.data.type === 'document') {
                   openUploadDialog(function (file) {
                       uploadDialogData.loading();
                       $("#btn-upload-modal-ok").hide();
                       pageAPI.update.document(pageData.id, pageData.name, file, function () {
                           uploadDialogData.success();
                       });
                   }, "Schimbați documentul");
               }
           });
           $(`#${pagePreviewIconId}`).on('click', function () {
                openDisplayDialog(`
                    <iframe src="${pageData.link}" style="width: 100%;height: 600px; margin-bottom:0;border: 0;border-radius: 0px;">
                `, pageData.link);
           });
       }
       callback();
    });

};

//Confirm Dialog
let confirmDialogData = {text: "", callback: function () {}};

// noinspection JSUnusedGlobalSymbols
let openConfirmDialog = function(text, callback) {
    confirmDialogData.text = text;
    confirmDialogData.callback = callback;

    $("#bd-confirm-modal-lg .display").html(text);
    $("#bd-confirm-modal-lg").modal("show");
};

//Page select
let pageSelectDialogData = {callback: function () {}};

// noinspection JSUnusedGlobalSymbols
let openPageSelectDialog = function(callback) {
    pageSelectDialogData.callback = callback;
    $("#bd-page-select-modal-lg").modal("show");
};

//Field edit
let fieldEditData = {callback: function () {}, title: "", fieldName: ""};

// noinspection JSUnusedGlobalSymbols
let openFieldEditDialog = function(callback, title, fieldName) {
    fieldEditData.callback = callback;
    fieldEditData.fieldName = fieldName;
    fieldEditData.title = title;
    $("#field-edit-modal-title").text(title);
    $("#label-field-edit-modal-input").text(fieldName + ": ");
    $("#bd-field-edit-modal-lg").modal("show");
};

// noinspection JSUnusedGlobalSymbols
let htmlFieldEditDialog = function(callback, title, html) {
    htmlEditData.callback = callback;
    htmlEditData.title = title;
    $("#html-edit-modal-title").text(title);
    $("#summernote-html-edit").summernote('code', html);
    $("#bd-html-edit-modal-lg").modal("show");
};

//Html edit
let htmlEditData = {callback: function () {}, title: "", fieldName: ""};

//Upload
let uploadDialogData = {callback: function () {}, title: "",
    success() {
        $("#upload-success").show();
        $("#upload-loading").hide();
    },
    loading() {
        $("#upload-loading").show();
        $("#upload-success").hide();
    }
};

let openUploadDialog = function(callback, title) {
    uploadDialogData.callback = callback;
    uploadDialogData.title = title;
    $("#file-input-upload")[0].value = '';
    $("#bd-upload-modal-lg").modal("show");
    $("#btn-upload-modal-ok").show();
};

//Display dialog
let displayDialogData = {link: ""};

let openDisplayDialog = function(html, link) {
    $("#display-modal-html-area").html(html);
    $("#bd-display-modal-lg").modal("show");
    $("#btn-display-modal-link").on("click", function() {
        $("#btn-confirm-modal-ok").hide();
        $("#title-confirm-dialog").text("Link");
        openConfirmDialog(`<a href ="${link}" target="_blank">${link}</a>`, function () {

        });
    });
};

//Create page
let pageCreationData = {name: "", type: "", data: null};
let createPage = function(type) {
    openFieldEditDialog(function (name) {
        $("#btn-upload-modal-ok").show();
        if(type === "document") {
            openUploadDialog(function (file) {
                uploadDialogData.loading();
                $("#btn-upload-modal-ok").hide();
                if(file) pageAPI.create.document(name, file, function () {
                    uploadDialogData.success();
                    loadPages(function () {
                        console.log("pages reloaded");
                    });
                })
            }, "Încărcați pagina");
        }
        else if(type === "html") {
            htmlFieldEditDialog(function (data) {
                pageAPI.create.html(name, data, function () {
                    $("#bd-html-edit-modal-lg").modal("hide");
                    loadPages(function () {
                        console.log("pages reloaded");
                    });
                });
            }, "Pagină nouă", `<h1>${name}</h1><br>`)
        }
    }, "Pagină nouă", "Numele paginii")
};

//Menu edit
let menuSortable = $("#menuList").sortable({
    connectWith: ".sortMenu",
});
$("#menuList").disableSelection();
let deleteCategory = function(uid) {
    $(uid).parent().remove();
};

let renameCategory = function(uid, menuName) {
    $(uid).parent().find('.btn-collapse-class').html(`<i class="fa-chevron-down fas" style="margin-right: 5px;"></i>${menuName}`);
};
let loadMenu = function() {
    $("#menuList").html("");
    menuAPI.getStructure(function (response) {
        let structure = JSON.parse(response);
        currentMenuStruct = structure;
        let i;
        for(i of structure) {
            if(!isNaN(i)) {
                let pageElemId = getUniqueID();
                let pageObject = currentPagesArray.find(element => element.id === i);
                if(pageObject !== undefined && i !== null)$("#menuList").append(`<li class="list-group-item" id="${pageElemId}" cms-data='{"page":${i}}'>${i}) ${pageObject.name} <i class="fas fa-trash" style="float: right"></i></li>`);
                else if(i !== null) $("#menuList").append(`<li class="list-group-item text-danger" id="${pageElemId}" cms-data='{"page":${i}}'> ${i}) Pagina inexistentă <i class="fas fa-trash" style="float: right"></i></li>`);
                if(i !== null && pageObject !== undefined)$(`#${pageElemId} > .fa-trash`).on("click", function () {
                    openConfirmDialog(`Ștergeți pagina "${pageObject.name}" din meniu?`, function () {
                        $(`#${pageElemId}`).remove();
                    });
                });
                else {
                    $(`#${pageElemId} > .fa-trash`).on("click", function () {
                        openConfirmDialog(`Ștergeți pagina inexistentă din meniu?`, function () {
                            $(`#${pageElemId}`).remove();
                        });
                    });
                }
            }
            else if(typeof i === 'object' && i !== null) {
                let uniqueId = getUniqueID();
                let sortableId = getUniqueID();
                let menuName = i.name;
                $("#menuList").append(`
<li class="list-group-item category-header" cms-data="${menuName}" style="padding: 5px;">
    <div class="d-flex">
        <a class="btn btn-block btn-info btn-collapse-inline-block-60 btn-collapse-class" data-toggle="collapse"
           href="#${uniqueId}" role="button" aria-expanded="false" aria-controls="${uniqueId}">
            <i class="fa-chevron-down fas" style="margin-right: 5px;"></i>${menuName}</a>
        <a class="btn btn-block btn-secondary btn-collapse-inline-block-20" href="javascript: openFieldEditDialog((name) => {changeCategoryName('#${uniqueId}',name);},'Redenumiți categoria &quot;${menuName}&quot;','nume')"><i class="fa-pencil-alt fas"></i></a>
        <a class="btn btn-block btn-danger btn-collapse-inline-block-20" href="javascript: openConfirmDialog('Ștergeți categoria &quot;${menuName}&quot;?', () => {deleteCategory('#${uniqueId}');})"  data-tooltip="Stergeți categoria"><i class="fa-trash fas"></i></a>
    </div>
    <div class="list-group-item collapse" id="${uniqueId}" style="width: 100%;margin:0;padding: 0;">
        <ul class="list-group sortMenu" id="${sortableId}" style="width: 100%;margin:0;padding: 0;min-height: 50px;"></ul>
    </div>
</li>`);
                for(j of i.pages) {
                    let pageObject = currentPagesArray.find(element => (element.id === j && true));
                    let pageElemId = getUniqueID();
                    if(pageObject !== undefined && j !== null)$(`#${sortableId}`).append(`<li class="list-group-item" id="${pageElemId}" cms-data='{"page":${j}}'>${j}) ${pageObject.name} <i class="fas fa-trash" style="float: right"></i></li>`);
                    else if(j !== null) $(`#${sortableId}`).append(`<li class="list-group-item text-danger" id="${pageElemId}" cms-data='{"page":${j}}'> ${j}) Pagina inexistenta <i class="fas fa-trash" style="float: right"></i></li>`);
                    if(j !== null && pageObject != null)$(`#${pageElemId} > .fa-trash`).on("click", function () {
                        openConfirmDialog(`Ștergeți pagina "${pageObject.name}" din meniu?`, function () {
                            $(`#${pageElemId}`).remove();
                        });
                    });
                    else {
                        $(`#${pageElemId} > .fa-trash`).on("click", function () {
                            openConfirmDialog(`Ștergeți pagina inexistentă din meniu?`, function () {
                                $(`#${pageElemId}`).remove();
                            });
                        });
                    }
                }
                $(`#${sortableId}`).sortable({
                    connectWith:".sortMenu",
                    receive(event, ui) {
                        if (ui.item.hasClass("category-header")) {
                            try {
                                $("#menuList").sortable("cancel");
                            } catch (e) {
                                console.log(e);
                            }
                        }
                    }
                });
                $(`#${sortableId}`).disableSelection();
            }
            menuSortable.sortable( "refresh" );
        }
    });

};

let changeCategoryName = function(selector, name) {
    $(selector).parent().attr("cms-data", name).find(`div > .btn-collapse-class`).html(`<i class="fa-chevron-down fas" style="margin-right: 5px;"></i>${name}`);
};

let addCategoryToMenu = function(name) {
            let uniqueId = getUniqueID();
            let sortableId = getUniqueID();
            let menuName = name;
            $("#menuList").append(`
<li class="list-group-item category-header" cms-data="${menuName}" style="padding: 5px;">
    <div class="d-flex">
        <a class="btn btn-block btn-info btn-collapse-inline-block-60 btn-collapse-class" data-toggle="collapse"
           href="#${uniqueId}" role="button" aria-expanded="false" aria-controls="${uniqueId}">
            <i class="fa-chevron-down fas" style="margin-right: 5px;"></i>${menuName}</a>
        <a class="btn btn-block btn-secondary btn-collapse-inline-block-20" href="javascript: openFieldEditDialog((name) => {changeCategoryName('#${uniqueId}',name);},'Redenumiți categoria &quot;${menuName}&quot;','nume')"><i class="fa-pencil-alt fas"></i></a>
        <a class="btn btn-block btn-danger btn-collapse-inline-block-20" href="javascript: openConfirmDialog('Ștergeți categoria &quot;${menuName}&quot;?', () => {deleteCategory('#${uniqueId}');})"  data-tooltip="Stergeți categoria"><i class="fa-trash fas"></i></a>
    </div>
    <div class="list-group-item collapse" id="${uniqueId}" style="width: 100%;margin:0;padding: 0;">
        <ul class="list-group sortMenu" id="${sortableId}" style="width: 100%;margin:0;padding: 0;min-height: 50px;"></ul>
    </div>
</li>`);
            $(`#${sortableId}`).sortable({
                connectWith:".sortMenu",
                receive(event, ui) {
                    if (ui.item.hasClass("category-header")) {
                        try {
                            $("#menuList").sortable("cancel");
                        } catch (e) {
                            console.log(e);
                        }
                    }
                }
            });
            $(`#${sortableId}`).disableSelection();

        menuSortable.sortable( "refresh" );
};

let sendUpdatedMenu = function() {
    currentMenuStruct = getMenuArray();
    menuAPI.updateStructure(JSON.stringify(currentMenuStruct), function () {
        menuAPI.regenerate(function () {
           console.log("menu updated and regenerated");
           iqwerty.toast.Toast('Meniul a fost publicat!');
        });
    });
};

$(() => {
    $("#btn-upload-gdrive").hide();
    //INIT
    loadPages(function () {
         loadMenu();
    });
    //Confirm modal
    $("#btn-confirm-modal-ok").on("click", function () {
        confirmDialogData.callback();
        $("#bd-confirm-modal-lg").modal("hide");
        $("#btn-confirm-modal-ok").show();
        $("#title-confirm-dialog").text("Confirmă");
    });
    $("#btn-confirm-modal-cancel").on("click", function () {
        $("#bd-confirm-modal-lg").modal("hide");
        $("#btn-confirm-modal-ok").show();
        $("#title-confirm-dialog").text("Confirmă");
    });

    //Page select modal
    $("#btn-page-select-modal-ok").on("click", function () {
        pageSelectDialogData.callback($("#page-select-choice :checked").attr("value") ? Number($("#page-select-choice :checked").attr("value")) : null);
        $("#bd-page-select-modal-lg").modal("hide");
        $("#page-select-choice :checked").prop('checked', false);
    });
    $("#btn-page-select-modal-cancel").on("click", function () {
        $("#bd-page-select-modal-lg").modal("hide");
        $("#page-select-choice :checked").prop('checked', false);
    });

    //Field edit modal
    $("#btn-field-edit-modal-ok").on("click", function () {
        fieldEditData.callback($("#tbx-field-edit-modal-input").val());
        $("#bd-field-edit-modal-lg").modal("hide");
        $("#tbx-field-edit-modal-input").val("");
        $("#label-field-edit-modal-input").text("");
    });
    $("#btn-field-edit-modal-cancel").on("click", function () {
        $("#bd-field-edit-modal-lg").modal("hide");
        $("#tbx-field-edit-modal-input").val("");
        $("#label-field-edit-modal-input").text("");
    });



    let renderExcelTableToHtml = function (tableObject) {
        let result = "<table class=\"table table-bordered\"><tbody>";
        let range = XLSX.utils.decode_range(tableObject["!ref"]);
        for(let i = range.s.r; i <= range.e.r; i++) {
            result += "<tr>";
            for(let j = range.s.c; j <= range.e.c; j++) {
                let cellContents = tableObject[XLSX.utils.encode_cell({r: i, c: j})].w;
                result += `<td>${cellContents}</td>`;
            }
            result += "</td>"
        }
        result += "</tbody></table><br>";
        return result;
    };

    var ExcelButton = function (context) {
        var ui = $.summernote.ui;

        // create button
        var button = ui.button({
            contents: '<i class="fa fa-file-excel"/> Import table',
            click: function () {
                openUploadDialog(function (file) {
                        var reader = new FileReader();
                        var name = file.name;
                        reader.onload = function(e) {
                            var data = e.target.result;

                            var workbook = XLSX.read(data, {type: 'binary'});
                            var htmlExcel = renderExcelTableToHtml(workbook.Sheets[Object.keys(workbook.Sheets)[0]]);
                            context.invoke('editor.pasteHTML', htmlExcel);
                        };
                        reader.readAsBinaryString(file);
                        $("#bd-upload-modal-lg").modal("hide");
                }, "Încărcați tabelul");

            }
        });

        return button.render();   // return button as jquery object
    };

    $("#summernote-html-edit").summernote({
        minHeight: 500,
        maxHeight: 600,
        height: 500,
        tooltip: false,
        onCreateLink : function(originalLink) {
            return originalLink; // return original link
        },
        dialogsInBody: true,
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['table', ['table']],
            ['insert', ['link', 'picture', 'video']],
            ['view', ['fullscreen', 'codeview', 'help']],
            ['mybutton', ['excel']]
        ],

        buttons: {
            excel: ExcelButton
        }
    });

    //Page edit modal
    $("#btn-html-edit-modal-ok").on("click", function () {
        htmlEditData.callback($("#summernote-html-edit").summernote("code"));
        $("#bd-html-edit-modal-lg").modal("hide");
        $("#summernote-html-edit").summernote("code", "");
    });
    $("#btn-html-edit-modal-cancel").on("click", function () {
        $("#bd-html-edit-modal-lg").modal("hide");
        $("#summernote-html-edit").summernote("code", "");
    });

    //Upload modal
    $("#btn-upload-modal-ok").on("click", function () {
        let fileData = $("#file-input-upload")[0].files[0];
        uploadDialogData.callback(fileData);
    });
    $("#btn-upload-modal-cancel").on("click", function () {
        $("#bd-upload-modal-lg").modal("hide");
        $("#upload-loading").hide();
        $("#upload-success").hide();
    });

    //Display modal
    $("#btn-display-modal-cancel").on("click", function () {
        $("#bd-display-modal-lg").modal("hide");
    });

    $("#btn-menu-new-category").on("click", function () {
        openFieldEditDialog(function (name) {
            addCategoryToMenu(name);
        }, "Categorie nouă", "nume categorie");
    });

    $("#btn-menu-add-page").on("click", function () {
        openPageSelectDialog(function (id) {
            let pageElemId = getUniqueID();
            let pageObject = currentPagesArray.find(element => element.id === id);
            if(pageObject !== undefined && id !== null)$("#menuList").append(`<li class="list-group-item" id="${pageElemId}" cms-data='{"page":${id}}'>${id}) ${pageObject.name} <i class="fas fa-trash" style="float: right"></i></li>`);
            else if(id !== null) $("#menuList").append(`<li class="list-group-item text-danger" id="${pageElemId}" cms-data='{"page":${id}}'> ${id}) Pagina inexistentă <i class="fas fa-trash" style="float: right"></i></li>`);
            if(id !== null)$(`#${pageElemId} > .fa-trash`).on("click", function () {
                openConfirmDialog(`Ștergeți pagina "${pageObject.name}" din meniu?`, function () {
                    $(`#${pageElemId}`).remove();
                });
            });
        });
    });
    onExtensionsLoad();
});

function getDomNodeDepth(node) {
    let currentNode = node, counter = 0;
    while(currentNode !== document.body) {
        currentNode = currentNode.parentNode;
        counter++;
    }
    return counter;
}

function getMenuArray() {
    let newArray = [];
    $("#menuList").children().each(function () {
        if($(this).hasClass("category-header")) {
            //is dropdown
            let dropdownName = $(this).attr("cms-data");
            let dropdownObject = {name: dropdownName, pages:[]};

            $(this).find("ul").children().each(function () {
                const pageDataObject = currentPagesArray.find(x => x.id === JSON.parse($(this).attr("cms-data")).page);
                if(pageDataObject === undefined) {
                    newArray.push(JSON.parse($(this).attr("cms-data")).page);
                    const pageId = JSON.parse($(this).attr("cms-data")).page;
                    const pageDataObject = currentPagesArray.find(x => x.id === JSON.parse($(this).attr("cms-data")).page);
                    if(pageDataObject === undefined){
                        $(this).html(`${pageId}) Pagina inexistentă <i class="fas fa-trash" style="float: right"></i>`);
                        $(this).css("color", "red");
                        let pageElem = $(this);
                        $(this).find(`.fa-trash`).on("click", function () {
                            openConfirmDialog(`Ștergeți pagina inexistentă din meniu?`, function () {
                                $(pageElem).remove();
                            });
                        });
                    }
                }
                dropdownObject.pages.push(JSON.parse($(this).attr("cms-data")).page);
            });
            newArray.push(dropdownObject);
        }
        else {
            //is entry
            newArray.push(JSON.parse($(this).attr("cms-data")).page);
            const pageId = JSON.parse($(this).attr("cms-data")).page;
            const pageDataObject = currentPagesArray.find(x => x.id === JSON.parse($(this).attr("cms-data")).page);
            if(pageDataObject === undefined){
                $(this).html(`${pageId}) Pagina inexistentă <i class="fas fa-trash" style="float: right"></i>`);
                $(this).css("color", "red");
                let pageElem = $(this);
                $(this).find(`.fa-trash`).on("click", function () {
                    openConfirmDialog(`Ștergeți pagina inexistentă din meniu?`, function () {
                        $(pageElem).remove();
                    });
                });
            }
        }
    });
    currentMenuStruct = newArray;
    return newArray;
}