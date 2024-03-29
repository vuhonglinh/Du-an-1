<?php
function construct()
{
    load_module('index');
}

function indexAction()
{
}

function ajaxAction()
{
    if (!empty($_POST['number'])) {
        $number = $_POST['number'];
        $upload = "img/";
        $targetFile = $upload . basename($_FILES["file"]["name"]);
        $duoiFile = ['jpg', 'png', 'jpeg', 'gif', 'tiff'];
        $duoiImg = pathinfo($targetFile, PATHINFO_EXTENSION);
        if (in_array($duoiImg, $duoiFile)) {
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $targetFile)) {
                $data = [
                    'targetFile' => $targetFile,
                    'number' => $number,
                ];
                echo json_encode($data);
            } else {
                echo json_encode(array('status' => 'error', 'number' => $number,));;
            }
        } else {
            echo json_encode(array('status' => 'error', 'number' => $number,));;
        }
    }
}

function ajaxUploadImageAction()
{
    $images = $_FILES['images'];
    $result = [];
    foreach ($images['name'] as $key => $name) {
        $tmp_name = $images['tmp_name'][$key]; // Lấy phần tử cụ thể trong mảng tmp_name
        $uploadPath = "img/" . basename($name);
        $duoiFile = ['jpg', 'png', 'jpeg', 'gif', 'tiff'];
        $duoiImg = pathinfo($uploadPath, PATHINFO_EXTENSION);

        if (in_array(strtolower($duoiImg), $duoiFile)) { // Chuyển đuôi file về chữ thường và kiểm tra
            if (move_uploaded_file($tmp_name, $uploadPath)) {
                $result[] = $name;
            } else {
                echo json_encode(array('status' => 'error', 'message' => 'Failed to move uploaded file.'));
                return false;
            }
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Invalid file type.'));
            return false;
        }
    }
    echo json_encode(array('status' => 'success', 'result' => $result));
}
function add_productAction() //Thêm sản phẩm
{
    global $error, $product_name, $product_code, $product_desc, $file, $images, $product_content, $status, $parent_id;
    if (isset($_POST['add_product'])) {
        $error = [];
        //Kiểm tra product_name
        if (empty($_POST['product_name'])) {
            $error['product_name'] = "Không được để trống";
        } else {
            $product_name = $_POST['product_name'];
        }
        //Kiểm tra product_code
        if (empty($_POST['product_code'])) {
            $error['product_code'] = "Không được để trống";
        } else {
            $product_code = $_POST['product_code'];
        }
        ////Kiểm tra product_desc
        if (empty($_POST['product_desc'])) {
            $error['product_desc'] = "Không được để trống";
        } else {
            $product_desc = $_POST['product_desc'];
        }
        ////Kiểm tra product_content
        if (empty($_POST['product_content'])) {
            $error['product_content'] = "Không được để trống";
        } else {
            $product_content = $_POST['product_content'];
        }
        ////Kiểm tra parent_id
        if (empty($_POST['parent_id'])) {
            $error['parent_id'] = "Không được để trống";
        } else {
            $parent_id = $_POST['parent_id'];
        }
        ////Kiểm tra status
        if (empty($_POST['status'])) {
            $error['status'] = "Không được để trống";
        } else {
            $status = $_POST['status'];
        }
        ////Kiểm tra file
        if (empty($_FILES['file']['name'])) {
            $error['file'] = "Không được để trống";
        } else {
            if (is_file_img($_FILES['file']['name'])) {
                move_uploaded_file($_FILES['file']['tmp_name'], "img/" . $_FILES['file']['name']);
                $file = $_FILES['file']['name'];
            } else {
                $error['file'] = 'Không đúng định dạng ảnh';
            }
        }
        //Kiểm tra ảnh chi tiết
        if (empty($_FILES['images'])) {
            $error['images'] = "Không được để trống";
        } else {
            foreach ($_FILES['images']['name'] as $item) {
                if (is_file_img($item)) {
                    $images = $_FILES['images']['name'];
                } else {
                    $error['images'] = 'Ảnh không đúng định dạng';
                }
            }
        }
        //Kiểm tra biến thể
        if (isset($_POST['ram_variants'])) {
            if (empty($_POST['ram_variants'])) {
                $error['variants'] = "Không được để trống biến thể";
            } else {
                foreach ($_POST['ram_variants'] as $itemIndex => $item) {
                    if (empty($item['name'])) {
                        $error['variants'] = "Không được để trống biến thể";
                    } else {
                        $vartians = $_POST['ram_variants'];
                    }
                    if (isset($item['colors'])) {
                        if (empty($item['colors'])) {
                            $error['variants'] = "Không được để trống biến thể";
                        } else {
                            foreach ($item['colors'] as $colorIndex => $v) {
                                if (empty($v['name']) || empty($v['price']) || empty($v['qty']) || empty($v['color'])) {
                                    $error['variants'] = "Không được để trống biến thể";
                                } else {
                                    $vartians = $_POST['ram_variants'];
                                }
                                if (filter_var($v['price'], FILTER_VALIDATE_INT) && $v['price'] > 0) {
                                } else {
                                    $error['variants'] = "Giá biến thể không đúng định dạng";
                                }
                                if (filter_var($v['qty'], FILTER_VALIDATE_INT) && $v['qty'] >= 0) {
                                } else {
                                    $error['variants'] = "Số lượng biến thể không đúng định dạng";
                                }
                                //Kiểm tra ảnh
                                if (is_file_img($_FILES['ram_variants']['name'][$itemIndex]['colors'][$colorIndex]['img'])) {
                                } else {
                                    $error['variants'] = 'Ảnh không đúng định dạng';
                                }
                            }
                        }
                    } else {
                        $error['variants'] = "Không được để trống biến thể màu sắc";
                    }
                }
            }
        }
        //Kết luận
        if (empty($error)) {
            $data_product = [
                'product_name' => $product_name,
                'product_code' => $product_code,
                'product_desc' => $product_desc,
                'product_thumb' => $file,
                'product_content' => $product_content,
                'status' => $status,
                'cat_id' => $parent_id,
                'creator' => $_SESSION['admin_login'],
            ];
            $id_product = add_product_data($data_product);
            foreach ($images as $value) { //Add ảnh chi tiết của sản phẩm
                $data_image = [
                    'product_id' => $id_product,
                    'image' => $value,
                ];
                add_detail_img($data_image);
            }
            //Thêm biến thể
            if (isset($vartians)) {
                foreach ($vartians as $itemIndex => $item) { //Thêm biến thể ram
                    $data_ram = [
                        'product_id' => $id_product,
                        'ram_name' => $item['name'],
                    ];
                    $ram_id = add_ram_vartians($data_ram); //Thêm thuộc tính ram
                    if (isset($item['colors'])) {
                        if (is_array($item['colors'])) {
                            foreach ($item['colors'] as $colorIndex => $v) {
                                //Xử lý ảnh
                                $targetFilePath = $_FILES['ram_variants']['name'][$itemIndex]['colors'][$colorIndex]['img'];
                                move_uploaded_file($_FILES['ram_variants']['tmp_name'][$itemIndex]['colors'][$colorIndex]['img'], "img/" . $targetFilePath);
                                $data_color = [
                                    'ram_id' => $ram_id,
                                    'product_id' => $id_product,
                                    'color_name' => $v['name'],
                                    'color_price' => $v['price'],
                                    'color' => $v['color'],
                                    'quantity' => $v['qty'],
                                    'image' => $targetFilePath,
                                ];
                                add_color_vartians($data_color); //Thêm biến thể màu sắc
                            }
                        }
                    }
                }
            }
            $error['account'] = "Thêm sản phẩm thành công";
        }
    }
    $data['list_category'] = list_category();
    load_view("add_product", $data);
}

function delete_productAction() //Xóa sản phẩm
{
    $id = (int)$_GET['id'];
    delete_product($id);
    delete_related($id); //Xóa các thuộc tính liên quan đến sản phẩm
    redirect("?mod=product&action=list_product");
}

function update_productAction() //Sửa sản phẩm
{
    global $error, $product_name, $product_code, $product_desc, $file, $product_content, $status, $parent_id;
    $id = $_GET['id'];
    $data['product'] = get_product_by_id($id); //Lấy sản phẩm
    $data['list_ram_var'] = get_ram_variants($id); //Lấy thuộc tính ram của sản phẩm theo id
    // $data['list_color_var'] = get_color_variants($id); //Lấy thuộc tính màu sắc của sản phẩm theo id
    $data['list_category'] = list_category();
    if (isset($_POST['update_product'])) {
        $error = [];
        //Kiểm tra product_name
        if (empty($_POST['product_name'])) {
            $error['product_name'] = "Không được để trống";
        } else {
            $product_name = $_POST['product_name'];
        }
        //Kiểm tra product_code
        if (empty($_POST['product_code'])) {
            $error['product_code'] = "Không được để trống";
        } else {
            $product_code = $_POST['product_code'];
        }
        ////Kiểm tra product_desc
        if (empty($_POST['product_desc'])) {
            $error['product_desc'] = "Không được để trống";
        } else {
            $product_desc = $_POST['product_desc'];
        }
        ////Kiểm tra product_content
        if (empty($_POST['product_content'])) {
            $error['product_content'] = "Không được để trống";
        } else {
            $product_content = $_POST['product_content'];
        }
        ////Kiểm tra parent_id
        if (empty($_POST['parent_id'])) {
            $error['parent_id'] = "Không được để trống";
        } else {
            $parent_id = $_POST['parent_id'];
        }
        ////Kiểm tra status
        if (empty($_POST['status'])) {
            $error['status'] = "Không được để trống";
        } else {
            $status = $_POST['status'];
        }
        //Kiểm tra file
        if (empty($_FILES['file']['name'])) {
            $file  = $data['product']['product_thumb'];
        } else {
            if (is_file_img($_FILES['file']['name'])) {
                $file = $_FILES['file']['name'];
            } else {
                $error['file'] = 'Không đúng định dạng ảnh';
            }
        }
        //Kiểm tra ảnh chi tiết khi sửa
        if (isset($_FILES['detail_img'])) {
            $detail_img = $_FILES['detail_img']['name'];
            foreach ($detail_img as $key => $value) {
                if (!empty($value)) {
                    if (is_file_img($value)) {
                        $detail_img = $_FILES['detail_img']['name'];
                    } else {
                        $error['images'] = 'Không đúng định dạng ảnh';
                    }
                }
            }
        } else {
            delete_detail_img_all($id); //Xóa tất cả các ảnh chi tiết
        }
        //Kiểm tra ảnh chi tiết khi được thêm
        if (isset($_FILES['images']['name'])) {
            foreach ($_FILES['images']['name'] as $key => $value) {
                if (is_file_img($value)) {
                    $images = $_FILES['images']['name'];
                } else {
                    $error['images'] = 'Không đúng định dạng ảnh';
                }
            }
        }
        //Kiểm tra biến thể cũ được cập nhật
        if (isset($_POST['update_ram_variants'])) {
            if (empty($_POST['update_ram_variants'])) {
            } else {
                foreach ($_POST['update_ram_variants'] as $itemIndex => $item) {
                    if (empty($item['name'])) {
                        $error['variants'] = "Không được để trống biến thể ram";
                    } else {
                    }
                    if (isset($item['colors'])) { //Kiểm tra biến cũ có thay đổi không
                        if (empty($item['colors'])) {
                            $error['variants'] = "Không được để trống biến thể màu sắc";
                        } else {
                            foreach ($item['colors'] as $colorIndex => $v) {
                                if (empty($v['name']) || empty($v['price']) || empty($v['qty']) || empty($v['color'])) {
                                    $error['variants'] = "Không được để trống biến thể";
                                }
                                if (filter_var($v['price'], FILTER_VALIDATE_INT) && $v['price'] > 0) {
                                } else {
                                    $error['variants'] = "Giá biến thể không đúng định dạng";
                                }
                                if (filter_var($v['qty'], FILTER_VALIDATE_INT) && $v['qty'] > 0) {
                                } else {
                                    $error['variants'] = "Số lượng biến thể không đúng định dạng";
                                }
                                if (empty($_FILES['update_ram_variants']['name'][$itemIndex]['colors'][$colorIndex]['img'])) { //Khi không có cập nhật
                                } else { //Ngược lại khi có ảnh phải chuẩn
                                    if (is_file_img($_FILES['update_ram_variants']['name'][$itemIndex]['colors'][$colorIndex]['img'])) {
                                    } else {
                                        $error['variants'] = "Ảnh biến thể không đúng định dạng";
                                    }
                                }
                            }
                        }
                    } else {
                        $error['variants'] = "Không được để trống biến thể màu sắc";
                    }
                    //Kiểm tra khi biến thể ram thêm mới màu sắc
                    if (isset($item['update'])) {
                        if (empty($item['update'])) {
                            $error['variants'] = "Không được để trống biến thể màu sắc";
                        } else {
                            foreach ($item['update'] as $updateIndex => $v) {
                                if (empty($v['name']) || empty($v['price']) || empty($v['qty']) || empty($v['color'])) {
                                    $error['variants'] = "Không được để trống biến thể";
                                }
                                if (filter_var($v['price'], FILTER_VALIDATE_INT) && $v['price'] > 0) {
                                } else {
                                    $error['variants'] = "Giá biến thể không đúng định dạng";
                                }
                                if (filter_var($v['qty'], FILTER_VALIDATE_INT) && $v['qty'] > 0) {
                                } else {
                                    $error['variants'] = "Số lượng biến thể không đúng định dạng";
                                }
                                //Kiểm tra ảnh
                                if (is_file_img($_FILES['update_ram_variants']['name'][$itemIndex]['update'][$updateIndex]['img'])) {
                                } else {
                                    $error['variants'] = "Ảnh biến thể không đúng định dạng";
                                }
                            }
                        }
                    }
                }
            }
        } else {
            $error['variants'] = "Không được để trống";
        }
        //Kiểm tra biến thể mới được thêm
        if (isset($_POST['ram_variants'])) {
            if (empty($_POST['ram_variants'])) {
                $error['add_variants'] = "Không được để trống biến thể";
            } else {
                foreach ($_POST['ram_variants'] as $itemIndex => $item) {
                    if (empty($item['name'])) {
                        $error['add_variants'] = "Không được để trống biến thể";
                    } else {
                        $vartians = $_POST['ram_variants'];
                    }
                    if (isset($item['colors'])) {
                        if (empty($item['colors'])) {
                            $error['add_variants'] = "Không được để trống biến thể";
                        } else {
                            foreach ($item['colors'] as $colorIndex => $v) {
                                if (empty($v['name']) || empty($v['price']) || empty($v['qty']) || empty($v['color'])) {
                                    $error['add_variants'] = "Không được để trống biến thể";
                                } else {
                                    $vartians = $_POST['ram_variants'];
                                }
                                if (filter_var($v['price'], FILTER_VALIDATE_INT) && $v['price'] > 0) {
                                } else {
                                    $error['add_variants'] = "Giá biến thể không đúng định dạng";
                                }
                                if (filter_var($v['qty'], FILTER_VALIDATE_INT) && $v['qty'] >= 0) {
                                } else {
                                    $error['add_variants'] = "Số lượng biến thể không đúng định dạng";
                                }
                                //Kiểm tra ảnh
                                if (is_file_img($_FILES['ram_variants']['name'][$itemIndex]['colors'][$colorIndex]['img'])) {
                                } else {
                                    $error['add_variants'] = 'Ảnh không đúng định dạng';
                                }
                            }
                        }
                    } else {
                        $error['add_variants'] = "Không được để trống biến thể màu sắc";
                    }
                }
            }
        }

        // Kết luận
        if (empty($error)) {
            //Cập nhật sản phẩm
            $data_product = [
                'product_name' => $product_name,
                'product_code' => $product_code,
                'product_desc' => $product_desc,
                'product_thumb' => $file,
                'product_content' => $product_content,
                'status' => $status,
                'cat_id' => $parent_id,
            ];
            update_product($id, $data_product); //Cập nhật sản phẩm
            // Kiểm tra ảnh chi tiết cũ
            if (isset($detail_img)) {
                $string_id = "";
                foreach ($detail_img as $key => $value) {
                    $string_id .= $key . " ,"; //Danh sách id ảnh tồn tại khi cập nhật
                    if (!empty($value)) { //Những file chữa ảnh được update
                        $data_detail_img = [
                            'image' => $value,
                        ];
                        update_detail_img_by_id($key, $data_detail_img); ////Cập nhật ảnh chi tiết cảu sản phẩm theo id anh chi tiết
                    }
                }
                $string_id = substr($string_id, 0, -1);
                remove_interval_detail_img($string_id, $id); //Xóa các id ảnh chi tiết nếu không tồn tại trong danh sách
            }
            if (isset($images)) { //Add ảnh chi tiết của sản phẩm
                foreach ($images as $value) {
                    $data_image = [
                        'product_id' => $id,
                        'image' => $value,
                    ];
                    add_detail_img($data_image);
                }
            }
            //Cập nhật biến thể
            $string_id = "";
            if (isset($_POST['update_ram_variants'])) {
                foreach ($_POST['update_ram_variants'] as $key => $item) {
                    $data_available_ram = [
                        'ram_name' => $item['name'],
                    ];
                    update_variants_ram($data_available_ram, $key); //Update ram sản sản phẩm
                    $string_id_color = "";
                    $string_id .= "{$key},";
                    if (isset($item['colors'])) {
                        foreach ($item['colors'] as $k => $v) {
                            $string_id_color .= "$k,";
                            //Cập nhật khi không có thay đổi về anh
                            if (empty($_FILES['update_ram_variants']['name'][$key]['colors'][$k]['img'])) { //Khi không có cập nhật
                                $data_update_color = [
                                    'color_name' => $v['name'],
                                    'color_price' => $v['price'],
                                    'color' => $v['color'],
                                    'quantity' => $v['qty'],
                                ];
                                update_variants_color($data_update_color, $k); //Cập nhật biến thể màu sắc theo id
                            } else { //Ngược lại khi có ảnh phải chuẩn
                                $targetFilePath = $_FILES['update_ram_variants']['name'][$key]['colors'][$k]['img'];
                                move_uploaded_file($_FILES['update_ram_variants']['tmp_name'][$key]['colors'][$k]['img'], "img/" . $targetFilePath);
                                $data_update_color = [
                                    'color_name' => $v['name'],
                                    'color_price' => $v['price'],
                                    'color' => $v['color'],
                                    'quantity' => $v['qty'],
                                    'image' => $targetFilePath,
                                ];
                                update_variants_color($data_update_color, $k); //Cập nhnật biến thể màu sắc theo id
                            }
                        }
                        if (!empty($string_id_color)) {
                            $string_id_color = substr($string_id_color, 0, -1);
                            delete_variant_color_isset_by_id("tb_color_variants", $string_id_color, $key, $id); //Xóa danh sach id ram không tồn tại
                        }
                    } else {
                        delete_all_variant_color($key); //Xóa toàn bộ biến thể màu theo ram_id
                    }
                    ///Phần update ram
                    if (isset($item['update'])) {
                        foreach ($item['update'] as $up => $vl) {
                            $targetFile = $_FILES['update_ram_variants']['name'][$key]['update'][$up]['img'];
                            move_uploaded_file($_FILES['update_ram_variants']['tmp_name'][$key]['update'][$up]['img'], "img/" . $targetFile);
                            $data_add_color = [
                                'ram_id' => $key,
                                'product_id' => $id,
                                'color_name' => $vl['name'],
                                'color_price' => $vl['price'],
                                'color' => $vl['color'],
                                'quantity' => $vl['qty'],
                                'image' => $targetFile
                            ];
                            add_variants_color($data_add_color); //Thêm biến thể màu sắc theo id ram cũ
                        }
                    }
                }

                if (!empty($string_id)) {
                    $string_id = substr($string_id, 0, -1);
                    delete_variant_isset_by_id("tb_ram_variants", $string_id,  $id); //Xóa danh sach id ram không tồn tại
                }
            } else {
                delete_all_variant_ram($id); //Xóa toàn bộ thuộc tính của sản phẩm theo product_id
            }
            //ADD biến thể khi được thêm vào
            //Thêm biến thể
            if (isset($vartians)) {
                foreach ($vartians as $itemIndex => $item) { //Thêm biến thể ram
                    $data_ram = [
                        'product_id' => $id,
                        'ram_name' => $item['name'],
                    ];
                    $ram_id = add_ram_vartians($data_ram); //Thêm thuộc tính ram
                    if (isset($item['colors'])) {
                        if (is_array($item['colors'])) {
                            foreach ($item['colors'] as $colorIndex => $v) {
                                //Xử lý ảnh
                                $targetFilePath = $_FILES['ram_variants']['name'][$itemIndex]['colors'][$colorIndex]['img'];
                                move_uploaded_file($_FILES['ram_variants']['tmp_name'][$itemIndex]['colors'][$colorIndex]['img'], "img/" . $targetFilePath);
                                $data_color = [
                                    'ram_id' => $ram_id,
                                    'product_id' => $id,
                                    'color_name' => $v['name'],
                                    'color_price' => $v['price'],
                                    'color' => $v['color'],
                                    'quantity' => $v['qty'],
                                    'image' => $targetFilePath,
                                ];
                                add_color_vartians($data_color); //Thêm biến thể màu sắc
                            }
                        }
                    }
                }
            }
            $error['account'] = "Sửa sản phẩm thành công";
        }
    }
    $data['list_ram_var'] = get_ram_variants($id); //Lấy thuộc tính ram của sản phẩm theo id
    $data['list_color_var'] = get_color_variants($id); //Lấy thuộc tính màu sắc của sản phẩm theo id
    $data['img_detail'] = get_detail_img_by_id($id); //Lấy ảnh chi tiết theo id sp
    $data['product'] = get_product_by_id($id);
    $data['list_category'] = list_category();
    load_view("update_product", $data);
}

function list_productAction() //Danh sách sản phẩm
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
    //Lấy danh sách danh mục sản phẩm
    $data['list_cat'] = get_list_cat();
    //lấy sô lượng bản ghi
    $data['num_products'] = num_products();
    $data['num_products_posted'] = num_products_posted();
    $data['num_products_pending'] = num_products_pending();
    //
    $status =  (!empty($_GET['status'])) ? $_GET['status'] : null;
    $cat_id =  (!empty($_GET['cat_id'])) ? $_GET['cat_id'] : null;
    $page =  (!empty($_GET['page'])) ? $_GET['page'] : 1;
    $num_rows = 5;
    $start = ($page - 1) * $num_rows;
    $data['num_rows'] = $num_rows;
    $data['start'] = $start;
    $data['list_products'] = get_list_products($start, $num_rows, $status, $cat_id); //Lấy danh sách sản phẩm
    load_view("list_product", $data);
}

function list_commentsAction()
{
    $id = $_GET['id'];
    $status = (!empty($_GET['status'])) ? $_GET['status'] : null;
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
                update_action_comment($action, $item);
            }
        }
    }
    //
    $data['product'] = get_product_by_id($id);
    $data['list_comments'] = list_comments($id, $status);
    load_view("list_comments", $data);
}

function delete_commentsAction()
{
    $delete = $_GET['delete'];
    $id = $_GET['id'];
    delete_comment_id($delete);
    redirect("?mod=product&action=list_comments&id={$id}");
}

function result_seachAction()
{
    $seach = (!empty($_POST['seach'])) ? $_POST['seach'] : null;
    redirect("?mod=product&action=seach_product&seach={$seach}");
}

function seach_productAction()
{
    $seach = (!empty($_GET['seach'])) ? $_GET['seach'] : null;
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
    $data['list_cat'] = get_list_cat();
    //lấy sô lượng bản ghi
    $data['num_products'] = num_products();
    $data['num_products_posted'] = num_products_posted();
    $data['num_products_pending'] = num_products_pending();
    //
    $data['list_seach'] = seach_product($seach);
    load_view('seach_product', $data);
}

function upload_img_color_ajaxAction()
{
    $file = $_FILES['file']; //Ảnh
    $color_id = $_POST['color_id']; //Id biến thể ảnh
    $tmp_name = $file['tmp_name']; // Lấy phần tử cụ thể trong mảng tmp_name
    $uploadPath = "img/" . basename($file['name']);
    $duoiFile = ['jpg', 'png', 'jpeg', 'gif', 'tiff'];
    $duoiImg = pathinfo($uploadPath, PATHINFO_EXTENSION);

    if (in_array(strtolower($duoiImg), $duoiFile)) { // Chuyển đuôi file về chữ thường và kiểm tra
        if (move_uploaded_file($tmp_name, $uploadPath)) {
            echo json_encode(array('status' => 'success',  'color_id' => $color_id, 'img' => $uploadPath));
            return true;
        } else {
            echo json_encode(array('status' => 'error', 'color_id' => $color_id, 'img' => 'Tải ảnh thất bại.'));
            return false;
        }
    } else {
        echo json_encode(array('status' => 'error',  'color_id' => $color_id, 'img' => 'Ảnh không đúng định dạng.'));
        return false;
    }
}
