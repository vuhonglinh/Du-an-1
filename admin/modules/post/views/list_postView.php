<?php
get_header();
get_sidebar();
?>
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">DANH SÁCH BÀI VIẾT</h3>
        </div>
        <div class="breadcrumb">
            <li class="breadcrumb-item"><a href="?mod=post&action=list_post" class="text-decoration-none">Tất cả(<?php echo $num_posts ?>)</a></li>
            <li class="breadcrumb-item"><a href="?mod=post&action=list_post&status=Đã đăng" class="text-decoration-none">Đã đăng(<?php echo $num_posts_posted ?>)</a></li>
            <li class="breadcrumb-item"><a href="?mod=post&action=list_post&status=Chờ xét duyệt" class="text-decoration-none">Chờ xét duyệt(<?php echo $num_posts_pending ?>)</a></li>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <form action="" class="form-group ml-2" method="post">
                <select name="action" id="action" class="form-control-sm form-check-inline">
                    <option value="">Tác vụ</option>
                    <option value="1">Xóa</option>
                    <option value="2">Đã đăng</option>
                    <option value="3">Chờ xét duyệt</option>
                </select>
                <button class="btn btn-sm btn-success" type="submit" name="btn_apply">Áp dụng</button>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>STT</th>
                            <th>Tiêu đề</th>
                            <th>Trạng thái</th>
                            <th>Người tạo</th>
                            <th>Thời gian</th>
                            <th style="width: 15%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_posts)) :
                            $count = $start;
                            foreach ($list_posts as $item) :
                        ?>
                                <tr>
                                    <td><input type="checkbox" name="checkitem[<?php echo $item['post_id'] ?>]" id="checkbox" value="<?php echo $item['post_id'] ?>" class="checkItem"></td>
                                    <td><?php echo ++$count; ?></td>
                                    <td><?php echo $item['post_title'] ?></td>
                                    <td><?php echo $item['status'] ?></td>
                                    <td><?php echo $item['creator'] ?></td>
                                    <td><?php echo $item['created_date'] ?></td>
                                    <td class="justify-content-between">
                                        <a class="btn btn-info btn-sm" href="?mod=post&action=update_post&id=<?php echo $item['post_id'] ?>" title="Sửa"><i class="fas fa-pencil-alt"></i>
                                            Sửa
                                        </a>
                                        <a onclick="return confirm('Bạn chắc muốn xóa bài viết này không?')" class="btn btn-danger btn-sm" href="?mod=post&action=delete_post&id=<?php echo $item['post_id'] ?>" title="Xóa"><i class="fas fa-trash"></i>
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach;
                        else : echo "KHÔNG CÓ SẢN PHẨM";
                        endif; ?>
                    </tbody>
                </table>
            </form>
        </div>
        <div class="card-footer clearfix">
            <?php
            echo get_padding($num_rows) ?>
        </div>
    </div>
</div>
<?php
get_footer();
?>