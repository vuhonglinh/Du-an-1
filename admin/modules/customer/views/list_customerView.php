<?php
get_header();
get_sidebar();
?>
<div class="content-wrapper">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">DANH SÁCH KHÁCH HÀNG</h3>
        </div>
        <div class="breadcrumb">
            <li class="breadcrumb-item"><a href="?mod=customer&action=list_customer" class="text-decoration-none">Tất cả(<?php echo $num_customer ?>)</a></li>
        </div>
        <!-- /.card-header -->
        <div class="card-body p-0">
            <form action="" class="form-group ml-2" method="post">
                <select name="action" id="action" class="form-control-sm form-check-inline">
                    <option value="">Tác vụ</option>
                    <option value="1">Xóa tài khoản</option>
                </select>
                <button class="btn btn-sm btn-success" type="submit" name="btn_apply">Áp dụng</button>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" name="checkAll" id="checkAll"></th>
                            <th>STT</th>
                            <th>Họ và tên</th>
                            <th>Số điện thoại</th>
                            <th>Email</th>
                            <th>Địa chỉ</th>
                            <th>Đơn hàng</th>
                            <th>Trạng thái</th>
                            <th>Thời gian lập tài khoản</th>
                            <th style="width: 15%;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($list_customer)) :
                            $count = 0;
                            foreach ($list_customer as $item) :
                        ?>
                                <tr>
                                    <td><input type="checkbox" name="checkitem[<?php echo $item['id'] ?>]" id="checkbox" value="<?php echo $item['id'] ?>" class="checkItem"></td>
                                    <td><?php echo ++$count ?></span>
                                    <td><?php echo $item['fullname'] ?></td>
                                    <td><?php echo $item['phone_number'] ?></td>
                                    <td><?php echo $item['email'] ?></span></td>
                                    <td><?php echo $item['address'] ?></span></td>
                                    <td><?php echo quality_order($item['id']) ?></td>
                                    <td><?php echo $is_active[$item['is_active']] ?></td>
                                    <td><?php echo $item['reg_date'] ?></span></td>
                                    <td class="justify-content-between">
                                        <a class="btn btn-info btn-sm" href="?mod=customer&action=detail_customer&id=<?php echo $item['id'] ?>" title="Sửa"><i class="fas fa-search"></i>
                                            Xem
                                        </a>
                                        <a class="btn btn-info btn-sm" href="?mod=customer&action=update_customer&id=<?php echo $item['id'] ?>" title="Sửa"><i class="fas fa-pencil-alt"></i>
                                            Sửa
                                        </a>
                                        <a onclick="return confirm('Bạn chắc muốn xóa khách hàng này không?')" class="btn btn-danger btn-sm" href="?mod=customer&action=delete_customer&id=<?php echo $item['id'] ?>" title="Xóa"><i class="fas fa-trash"></i>
                                            Xóa
                                        </a>
                                    </td>
                                </tr>
                        <?php endforeach;
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