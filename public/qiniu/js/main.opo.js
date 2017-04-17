/*global Qiniu */
/*global plupload */
/*global FileProgress */
/*global hljs */

$(function() {
    var uploader = Qiniu.uploader({
        runtimes: 'html5,flash,html4',
        browse_button: 'pickfiles',
        container: 'container',
        drop_element: 'container',
        multi_selection: false,
        //max_file_size: '100mb',
        flash_swf_url: 'js/plupload/Moxie.swf',
        dragdrop: true,
        chunk_size: '4mb',
        uptoken_url: $('#uptoken_url').val(),
        domain: $('#domain').val(),
        auto_start: true,
        // filters : {
        //     prevent_duplicates: true,
        //     // Specify what files to browse for
        //     mime_types: [
        //         {title : "视频文件", extensions : "flv,mpg,mpeg,avi,wmv,mov,asf,rm,rmvb,mkv,m4v,mp4"}
        //     ]
        // },
        init: {
            'FilesAdded': function(up, files) {
                if($('input[name="upload_file"]').val()){
                    swal("请先取消当前上传");
                    up.splice(1,999);
                }else if($('input[name="video_original"]').val()){
                    swal("请先删除当前上传");
                    up.splice(0,999);
                }else{
                    $('table').show();
                    $('#success').hide();
                    plupload.each(files, function(file) {
                        var progress = new FileProgress(file, 'fsUploadProgress');
                        progress.setStatus("等待...");
                        progress.bindUploadCancel(up);
                    });
                }
            },
            'BeforeUpload': function(up, file) {
                var Wrapper = $('<div class="m-t-20"/>');
                var cancelPlayBtn = $('<span class="origin-video btn  btn-primary delete-btn m-l-5" style="display:none">删除文件</span>');
                // var progress = new FileProgress(file, 'fsUploadProgress');
                // var chunk_size = plupload.parseSize(this.getOption('chunk_size'));
                // if (up.runtime === 'html5' && chunk_size) {
                //     progress.setChunkProgess(chunk_size);
                // }
                // uploader.destroy();
            },
            'UploadProgress': function(up, file) {
                var progress = new FileProgress(file, 'fsUploadProgress');
                var chunk_size = plupload.parseSize(this.getOption('chunk_size'));

                progress.setProgress(file.percent + "%", file.speed, chunk_size);
            },
            'UploadComplete': function() {
                // $('#success').show();
            },
            'FileUploaded': function(up, file, info) {
                var progress = new FileProgress(file, 'fsUploadProgress');
                progress.setComplete(up, info);
            },
            'Error': function(up, err, errTip) {
                // $('table').show();
                // console.log(2);
                // var progress = new FileProgress(err.file, 'fsUploadProgress');
                // progress.setError();
                // progress.setStatus(errTip);
                swal(errTip)
            }
        }
    });

    uploader.bind('FileUploaded', function() {
        console.log('hello man,a file is uploaded');
    });

    $('#container').on(
        'dragenter',
        function(e) {
            e.preventDefault();
            $('#container').addClass('draging');
            e.stopPropagation();
        }
    ).on('drop', function(e) {
        e.preventDefault();
        $('#container').removeClass('draging');
        e.stopPropagation();
    }).on('dragleave', function(e) {
        e.preventDefault();
        $('#container').removeClass('draging');
        e.stopPropagation();
    }).on('dragover', function(e) {
        e.preventDefault();
        $('#container').addClass('draging');
        e.stopPropagation();
    });


    $('body').on('click', 'table button.btn', function() {
        $(this).parents('tr').next().toggle();
    });


    $('tbody').on('click', '.delete-btn', function() {
        var that = $(this);
        swal({
            title: "确定删除?",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "删除",
            cancelButtonText: "取消",
            closeOnConfirm: false
        }, function () {
            var keya = that.data('keya');
            $.ajax({
                url: $('#delete_url').val(),
                data: {keya:keya,vid:$('#id').val()},
                async: false,
                success: function (res) {
                    if (res.code == 0) {
                        swal(res.message, "", "success");
                        that.closest('tr').remove();
                        uploader.splice(0,999);
                        $('#success').hide();
                    } else {
                        swal(res.message);
                    }
                }
            });
        });
    });
});
