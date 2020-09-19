<div class="modal-dialog modal-lg">
    <div class="modal-content">
        <div class="modal-header">
            <h6 class="modal-title m-0">Products (<?= $category['name'] ?> <?= count($products) ?>)</h6>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body m-0 p-0">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th><?= __('admin.image') ?></th>
                            <th width="220px"><?= __('admin.product_name') ?></th>
                            <th><?= __('admin.price') ?></th>
                            <th><?= __('admin.sku') ?></th>
                            <th><?= __('admin.display') ?></th>
                            <th><?= __('admin.action') ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $key => $product) { ?>
                            <tr>
                                <td><img width="30px" height="30px" src="<?php echo resize('assets/images/product/upload/thumb/'. $product['product_featured_image'] ,100,100) ?>" ></td>
                                <td><?php echo $product['product_name'];?></td>
                                <td><?php echo c_format($product['product_price']); ?></td>
                                <td><?php echo $product['product_sku'];?></td>
                                <td class="txt-cntr"><?= $product['on_store'] == '1' ? 'Yes' : 'No' ?></td>
                                <td class="txt-cntr">
                                    <a class="btn btn-sm btn-primary" onclick="return confirm('Are you sure you want to Edit?');" href="<?php echo base_url();?>admincontrol/updateproduct/<?php echo $product['product_id'];?>"><i class="fa fa-edit cursors" aria-hidden="true"></i></a>
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url('admincontrol/productupload/'. $product['product_id']);?>"><i class="fa fa-image cursors"></i></a>
                                    <a class="btn btn-sm btn-primary" href="<?php echo base_url('admincontrol/videoupload/'. $product['product_id']);?>"><i class="fa fa-youtube cursors"></i></a>
                                </td>
                            </tr>
                        <?php } ?>

                        <?php if(!$products){ ?>
                            <tr>
                                <td colspan="100%" class="text-center">No Products on this category</td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
        </div>
    </div>
</div>