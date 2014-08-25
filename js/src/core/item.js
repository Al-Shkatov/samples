
function Item(){
}
Item.prototype = {
    type:null,
    name:null,
    can_be_dropped:true,
    can_be_equiped:true,
    equip_to:function(inventory){
        /**
         * @todo Make Inventory to tower
         */
        if(inventory instanceof Inventory){
            inventory.add_item(this);
        }
    }
};