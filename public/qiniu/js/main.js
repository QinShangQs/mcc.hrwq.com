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
        filters : {
            prevent_duplicates: true,
            // Specify what files to browse for
            mime_types: [
                {title : "视频文件", extensions : "flv,mpg,mpeg,avi,wmv,mov,asf,rm,rmvb,mkv,m4v,mp4"}
            ]
        },
        init: {
            'FilesAdded': function(up, files) {
                // var free_time = $('input[name="free_time"]').val();
                // if(!free_time>0){
                //     swal("请先填写试看时长");
                //     up.splice(0,999);
                //     $('#fsUploadProgress').empty();
                //     return;
                // }
                if($('input[name="upload_file"]').val()){
                    swal("请先取消当前上传");
                    up.splice(1,999);
                }else if($('input[name="video_original"]').val()){
                    swal("请先删除当前上传");
                    up.splice(0,999);
                }else{
                    up.setOption("multipart_params",{'free_time':free_time});
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
                var cancelPlayBtn = $('<span class="origin-video btn  btn-primary delete-btn m-l-5" style="display:none">删除视频</span>');
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

    function initPlayer(vLink) {

        if ($("#video-embed").length) {
            return;
        }

        var vType = function() {
            $.ajaxSetup({
                headers: ''
            });
            var type = '';
            $.ajax({
                url: vLink + "?stat",
                async: false
            }).done(function(info) {
                type = info.mimeType;
                if (type == 'application/x-mpegurl') {
                    type = 'application/x-mpegURL';
                }
            });

            return type;
        };

        var player = $('<video id="video-embed" class="video-js vjs-default-skin vjs-big-play-centered" style="width: 100%;height: 500px;"></video>');
        $('#video-container').empty();
        $('#video-container').append(player);

        console.log('=======>>Type:', vType(), '====>>vLink:', vLink);
        var poster = vLink + '?vframe/jpg/offset/2';
        videojs('video-embed', {
            "width": "100%",
            "height": "500px",
            "controls": true,
            "autoplay": false,
            "preload": "auto",
            "poster": poster
        }, function() {
            this.src({
                type: vType(),
                src: vLink
            });
        });
    }

    function disposePlayer() {
        if ($("#video-embed").length) {
            $('#video-container').empty();
            // _V_('video-embed').dispose();
            var player = videojs('video-embed');
            player.dispose();
        }
    }


    $('#myModal-video').on('hidden.bs.modal', function() {
        disposePlayer();
    });

    $('tbody').on('click', '.play-btn', function() {
        $('#myModal-video').modal();
        var url = $(this).data('url');
        initPlayer(url);
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
            var keyb = that.data('keyb');
            var keyf = that.data('keyf');
            $.ajax({
                url: $('#delete_url').val(),
                data: {keya:keya,keyb:keyb,keyf:keyf,vid:$('#id').val()},
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
