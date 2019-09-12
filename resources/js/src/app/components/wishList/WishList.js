const NotificationService = require("../../services/NotificationService");

import TranslationService from "../../services/TranslationService";
import Vue from "vue";
import { mapState, mapActions } from "vuex";

Vue.component("wish-list", {

    props:
    {
        template:
        {
            type: String,
            default: "#vue-wish-list"
        },

        initIds:
        {
            type: Array,
            default: () => []
        }
    },

    data()
    {
        return {
            isLoading: false,
            wishListCount: {}
        };
    },

    computed: mapState({
        wishListItems: state => state.wishList.wishListItems,
        wishListIds: state => state.wishList.wishListIds
    }),

    created()
    {
        this.$store.commit("setWishListIds", this.initIds);

        this.isLoading = true;
        this.initWishListItems(this.wishListIds).then(
            response =>
            {
                this.isLoading = false;
            },
            error =>
            {
                this.isLoading = false;
            });
    },

    methods:
    {
        removeItem(item)
        {
            this.removeWishListItem(item)
                .then(() => NotificationService.success(
                    TranslationService.translate("Ceres::Template.wishListRemoved")
                ));
        },
        ...mapActions([
            "initWishListItems",
            "removeWishListItem"
        ])
    }
});
