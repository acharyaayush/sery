<?php
$unread_notification_list = "";
if(!empty($this->unread_notification)){
    foreach ($this->unread_notification as $value) {
        if($value['order_status'] == 0){#pending
            $icon_bg_class = 'bg-info';
            $icon_class = 'fas fa-shopping-cart';
        }else if($value['order_status'] == 1){#accepted or assigned
            $icon_bg_class = 'bg-success';
            $icon_class = 'fas fa-check';
        }else if($value['order_status'] == 2){#on the way
            $icon_bg_class = 'bg-primary';
            $icon_class = 'fas fa-road';
        }else if($value['order_status'] == 3){#started
            $icon_bg_class = 'bg-primary';
            $icon_class = 'fas fa-wrench';
        }else if($value['order_status'] == 4){#completed
            $icon_bg_class = 'bg-info';
            $icon_class = 'fas fa-tasks';
        }else if($value['order_status'] == 6){#order cancelled by customer
            $icon_bg_class = 'bg-danger';
            $icon_class = 'fas fa-exclamation-triangle';
        }

       $time_ago = get_time_ago($value['created_at']);

        $unread_notification_list.= '<a href="'.base_url('admin/order_detail/'.$value['order_id'].'').'" class="dropdown-item">
                                        <div class="dropdown-item-icon '.$icon_bg_class.' text-white">
                                            <i class="'.$icon_class.'"></i>
                                        </div>
                                        <div class="dropdown-item-desc">
                                            '.$value['title'].'
                                            <div class="time">'. $time_ago.'</div>
                                        </div>
                                    </a>';
    }
}else{
     $unread_notification_list = '<p class="text-center">No data found<p>';
}
?>
<?php echo $unread_notification_list ;?>