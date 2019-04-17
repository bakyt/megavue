<template>
    <div class="home-page">
        <banner/>
        <stores/>
        <!--<deals/>-->
        <!--<popular-categories/>-->
        <!--<banner2/>-->
        <!--<arrivals/>-->
        <!--<best-sellers/>-->
        <!--<adverts/>-->
        <!--<trends/>-->
        <!--<reviews/>-->
        <!--<recently-viewed/>-->
        <!--<brands/>-->
        <newsletter/>
    </div>
</template>

<script>
    import Banner from './home/Banner.vue'
    import Stores from './home/Stores.vue'
//    import Deals from './home/Deals.vue'
//    import PopularCategories from './home/PopularCategories.vue'
//    import Banner2 from './home/Banner2.vue'
//    import Arrivals from './home/Arrivals.vue'
//    import BestSellers from './home/BestSellers.vue'
//    import Adverts from './home/Adverts.vue'
//    import Trends from './home/Trends.vue'
//    import Reviews from './home/Reviews.vue'
//    import RecentlyViewed from './home/RecentlyViewed.vue'
//    import Brands from './home/Brands.vue'
    import Newsletter from './home/Newsletter.vue'
    export default {
        components: {
            banner: Banner,
            stores: Stores,
//            deals: Deals,
//            "popular-categories": PopularCategories,
//            banner2: Banner2,
//            arrivals: Arrivals,
//            "best-sellers": BestSellers,
//            adverts: Adverts,
//            trends: Trends,
//            reviews: Reviews,
//            "recently-viewed": RecentlyViewed,
//            brands: Brands,
            newsletter: Newsletter
        }
    }
</script>
