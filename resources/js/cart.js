import store from './store'

let cart = {
    notify:null,
    $lang: null,
    changeLang(lang){
        this.$lang = lang;
        if(this.notify) this.notify.update({
            title: this.$lang.app.success,
            text: this.$lang.cart.added_to_cart
        });
    },
    init(lang){
        this.changeLang(lang);
        if(Boolean(sessionStorage.getItem('fav'))){
            const favs = JSON.parse(sessionStorage.getItem('fav'));
            store.dispatch('setFavorites', favs)
                .then(()=>sessionStorage.removeItem('fav'));
        }
        if(Boolean(sessionStorage.getItem('cart'))){
            const shoppingCart = JSON.parse(sessionStorage.getItem('cart'));
            store.dispatch('setCart', shoppingCart)
                .then(()=>sessionStorage.removeItem('cart'));
        }
        window.onbeforeunload = () => {
            if(store.getters.cart.totalQuantity>0) sessionStorage.setItem('cart', JSON.stringify(store.getters.cart))
            if(store.getters.favorites.length>0) sessionStorage.setItem('fav', JSON.stringify(store.getters.favorites))
        }
    },
    addItem(product, quantity, color){
        let item = {
            id:product.id,
            title:product.title,
            image:product.image,
            color:color || null,
            price:product.sale>0?parseInt(product.price/100*(100-product.sale)):product.price,
            quantity: quantity
        };
        item.total = item.price*quantity;
        let shoppingCart = store.getters.cart;
        let currentStore = null;
        shoppingCart.stores.filter(storeItem => {
            if(storeItem.id === product.store.id) {
                currentStore = storeItem;
            }
        });
        if(currentStore){
            let currentItem = null;
            currentStore.items.filter(storeItemProduct => {
                if(storeItemProduct.id === item.id && (color && color.id === storeItemProduct.color.id || !color)){
                    currentItem = storeItemProduct;
                }
            });
            if(currentItem){
                currentItem.quantity+=item.quantity;
                currentItem.total+=item.total;
            }
            else currentStore.items.push(item);
            currentStore.total+=item.total;
            currentStore.quantity+=item.quantity;
        }
        else {
            currentStore = {
                id: product.store.id,
                name: product.store.name,
                icon: product.store.icon,
                slug: product.store.slug,
                items: [item],
                total: item.total,
                quantity: item.quantity
            };
            shoppingCart.stores.push(currentStore);
        }
        shoppingCart.totalPrice+=item.total;
        shoppingCart.totalQuantity+=item.quantity;
        store.dispatch('setCart', shoppingCart)
            .then(()=>{
                if(!this.notify) {
                    this.notify = new PNotify({
                        title: this.$lang.app.success,
                        text: this.$lang.cart.added_to_cart,
                        type: "success",
                        icon: "fa fa-check"
                    });
                }
                else {
                    this.notify.open()
                }
            });
    },
    incrementItem(item, storeItem, quantity){
        quantity = parseInt(quantity || 1);
        if(quantity < 1) quantity = 1;
        let shoppingCart = store.getters.cart;
        const storeIndex = shoppingCart.stores.indexOf(storeItem);
        if(storeIndex > -1) {
            let storeItemFilter = shoppingCart.stores[storeIndex];
            if (storeItemFilter.id === storeItem.id) {
                const itemIndex = storeItemFilter.items.indexOf(item);
                if (itemIndex > -1) {
                    let itemFilter = storeItemFilter.items[itemIndex];
                    let total = quantity*(item.sale>0?parseInt(item.price/100*(100-item.sale)):item.price);
                    itemFilter.quantity += quantity;
                    itemFilter.total += total;
                    storeItemFilter.quantity += quantity;
                    storeItemFilter.total += total;
                    shoppingCart.totalQuantity += quantity;
                    shoppingCart.totalPrice += total;
                }
            }
        }
        store.dispatch('setCart', shoppingCart);
    },
    decrementItem(item, storeItem, quantity){
        if(item.quantity > 1){
            quantity = parseInt(quantity || 1);
            if(quantity < 1) quantity = 1;
            let shoppingCart = store.getters.cart;
            const storeIndex = shoppingCart.stores.indexOf(storeItem);
            if(storeIndex > -1) {
                let storeItemFilter = shoppingCart.stores[storeIndex];
                if (storeItemFilter.id === storeItem.id) {
                    const itemIndex = storeItemFilter.items.indexOf(item);
                    if (itemIndex > -1) {
                        let itemFilter = storeItemFilter.items[itemIndex];
                        let total = quantity*(item.sale>0?parseInt(item.price/100*(100-item.sale)):item.price);
                        itemFilter.quantity -= quantity;
                        itemFilter.total -= total;
                        storeItemFilter.quantity -= quantity;
                        storeItemFilter.total -= total;
                        shoppingCart.totalQuantity -= quantity;
                        shoppingCart.totalPrice -= total;
                    }
                }
            }
            store.dispatch('setCart', shoppingCart);
        }
    },
    removeItem(item, storeItem){
        let shoppingCart = store.getters.cart;
        const storeIndex = shoppingCart.stores.indexOf(storeItem);
        if(storeIndex > -1) {
            let storeItemFilter = shoppingCart.stores[storeIndex];
            if (storeItemFilter.id === storeItem.id) {
                const itemIndex = storeItemFilter.items.indexOf(item);
                if (itemIndex > -1) {
                    storeItemFilter.quantity -= item.quantity;
                    storeItemFilter.total -= item.total;
                    shoppingCart.totalQuantity -= item.quantity;
                    shoppingCart.totalPrice -= item.total;
                    storeItemFilter.items.splice(itemIndex, 1);
                    if(storeItemFilter.items.length === 0) shoppingCart.stores.splice(storeIndex, 1);
                }
            }
        }
        store.dispatch('setCart', shoppingCart);
    },
    removeStore(storeItem){
        let shoppingCart = store.getters.cart;
        const storeIndex = shoppingCart.stores.indexOf(storeItem);
        if(storeIndex > -1) {
            shoppingCart.totalQuantity -= storeItem.quantity;
            shoppingCart.totalPrice -= storeItem.total;
            shoppingCart.stores.splice(storeIndex, 1);
        }
        store.dispatch('setCart', shoppingCart);
    },
    truncate(){
        store.dispatch('setCart', {
            stores:[],
            totalPrice:0,
            totalQuantity:0
        })
    }
};

export default cart
