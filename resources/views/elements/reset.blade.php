<div class="modal fade" id="reset-password" tabindex="-1" role="dialog" aria-labelledby="resetPassword" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">关闭</span></button>
                <h4 class="modal-title" id="resetPassword">重置密码</h4>
            </div>
            <div class="modal-body">
                <form class="form-horizontal" action="{{ route('admin.password') }}" role="form">
                    <div class="form-group">
                        <label for="old-password" class="col-sm-2 control-label">原密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="old_password" class="form-control" id="old-password" placeholder="原密码">
                            <div class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new-password" class="col-sm-2 control-label">密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="new_password" class="form-control" id="new-password" placeholder="密码">
                            <div class="text-danger"></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="new-password2" class="col-sm-2 control-label">确认密码</label>
                        <div class="col-sm-10">
                            <input type="password" name="new_password_confirmation" class="form-control" id="new-password2" placeholder="确认密码">
                            <div class="text-danger"></div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-primary">提交</button>
            </div>
        </div>
    </div>
</div>