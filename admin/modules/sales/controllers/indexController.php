<?php
function construct()
{
    load_module('index');
}

function indexAction()
{
}

function list_orderAction()
{
    //Tác vụ
    if (isset($_POST['btn_apply'])) {
        if (!empty($_POST['action'])) {
            $action = $_POST['action'];
        } else {
            $action = 0;
        }
        if (!empty($_POST['checkitem'])) {
            $checkitem = $_POST['checkitem'];
            foreach ($checkitem as $item) {
                update_action($action, $item);
            }
        }
    }
    //
    $data['num_orders'] = num_orders(); //Tổng đơn hàng
    $data['num_posts_pending'] = num_posts_pending(); //Chờ xác nhận
    $data['num_prepare_orders'] = num_prepare_orders(); //Chuẩn bị đơn hàng
    $data['num_orders_delivery'] = num_orders_delivery(); //Đang giao hàng
    $data['num_orders_success'] = num_orders_success(); //Thành công
    $data['num_orders_cancelled'] = num_orders_cancelled(); //Đã hủy
    $status = (!empty($_GET['status'])) ? $_GET['status'] : null;
    $page = (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $num_rows = 10;
    $start = ($page - 1) * $num_rows;
    $data['start'] = $start;
    $data['num_rows'] = $num_rows;
    $data['list_order'] = list_order($start, $num_rows, $status);
    $data['total_order'] = total_order();
    load_view("list_order", $data);
}

function detail_orderAction()
{
    $id = $_GET['id'];
    if (isset($_POST['sm_status'])) {
        //Kiểm tra status
        if (!empty($_POST['status'])) {
            $status = $_POST['status'];
        }
        $data_status = [
            'status' => $status
        ];
        //Kết luận
        add_status_by_id($data_status, $id);
    }
    $data['status_order'] = [
        1 => 'Chờ xác nhận',
        2 => 'Chuẩn bị đơn hàng',
        3 => 'Đang giao hàng',
    ];
    $data['detail_order'] = detail_order($id);
    $data['list_product'] = json_decode($data['detail_order']['order_buy'], true);
    load_view("detail_order", $data);
}

function delete_orderAction()
{
    $id = $_GET['id'];
    delete_order($id);
    redirect("?mod=sales&action=list_order");
}


function result_seachAction()
{
    $seach = (!empty($_POST['seach'])) ? trim($_POST['seach']) : null;
    // //Tác vụ
    if (isset($_POST['btn_apply'])) {
        if (!empty($_POST['action'])) {
            $action = $_POST['action'];
        } else {
            $action = 0;
        }
        if (!empty($_POST['checkitem'])) {
            $checkitem = $_POST['checkitem'];
            foreach ($checkitem as $item) {
                update_action($action, $item);
            }
        }
    }
    $data['num_orders'] = num_orders(); //Tổng đơn hàng
    $data['num_posts_pending'] = num_posts_pending(); //Chờ xác nhận
    $data['num_prepare_orders'] = num_prepare_orders(); //Chuẩn bị đơn hàng
    $data['num_orders_delivery'] = num_orders_delivery(); //Đang giao hàng
    $data['num_orders_success'] = num_orders_success(); //Thành công
    $data['num_orders_cancelled'] = num_orders_cancelled(); //Đã hủy
    $data['list_order_seach'] = list_order_seach($seach); //Danh sách tìm kiếm
    $data['total_order'] = total_order();
    load_view("seach_order", $data);
}
