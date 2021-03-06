<template>
    <div v-if="filterListBulk">
        <item-filter v-for="facet in facets"
            :facet="facet"
            :key="facet.id"
            :padding-classes="paddingClasses"
            :padding-inline-styles="paddingInlineStyles">
        </item-filter>
    </div>

    <div v-else class="filter-wrapper" v-show="facets && facets.length > 0">
        <a class="btn btn-link filter-toggle" data-toggle="collapse" :href="'#filter-collapse_' + _uid" aria-expanded="false" :aria-controls="'filter-collapse_' + _uid">
            <i class="fa fa-sliders default-float" aria-hidden="true"></i> {{ $translate("Ceres::Template.itemFilter") }}
        </a>

        <div v-open-filter-toolbar class="filter-collapse collapse" :id="'filter-collapse_' + _uid">
            <div class="container-max page-content component-loading" :class="{ 'is-loading': isLoading }">
                <div class="card-columns">
                    <item-filter v-for="facet in facets" :facet="facet" :key="facet.id"></item-filter>
                </div>

                <div class="row">
                    <div class="col-12 text-right">
                        <button type="button" class="btn btn-primary btn-medium-large" data-toggle="collapse" :href="'#filter-collapse_' + _uid" :aria-controls="'filter-collapse_' + _uid">
                            <i class="fa fa-times" aria-hidden="true"></i>
                            <span>{{ $translate("Ceres::Template.itemClose") }}&nbsp;</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import UrlService from "../../../services/UrlService";
import { mapState } from "vuex";
import ItemFilter from "./ItemFilter.vue";

export default {

    name: "item-filter-list",

    components:
    {
        ItemFilter
    },

    props: {
        filterListBulk: Boolean,
        facetData:
        {
            type: Array,
            default()
            {
                return [];
            }
        },
        allowedFacetsTypes:
        {
            type: Array,
            default: () => []
        },
        paddingClasses:
        {
            type: String,
            default: null
        },
        paddingInlineStyles:
        {
            type: String,
            default: null
        }
    },

    data()
    {
        return {
            initialSelectedFacets: [],
            initialPriceMin: "",
            initialPriceMax: ""
        };
    },

    computed:
    {
        ...mapState({
            facets(state)
            {
                if (!this.allowedFacetsTypes.length)
                {
                    return state.itemList.facets;
                }

                return state.itemList.facets
                    .filter(facet => this.allowedFacetsTypes.includes(facet.id) || this.allowedFacetsTypes.includes(facet.type));
            },
            isLoading: state => state.itemList.isLoading,
            selectedFacets: state => state.itemList.selectedFacets
        })
    },

    created()
    {
        this.$store.commit("addFacets", this.facetData);

        this.initSelectedFacets();
    },

    methods:
    {
        initSelectedFacets()
        {
            const urlParams = UrlService.getUrlParams(document.location.search);

            let selectedFacets = [];

            if (urlParams.facets)
            {
                selectedFacets = urlParams.facets.split(",");
            }

            if (this.initPriceFacet(urlParams))
            {
                selectedFacets.push("price");
            }

            if (selectedFacets.length > 0)
            {
                this.$store.commit("setSelectedFacetsByIds", selectedFacets);
            }

            this.initialSelectedFacets = selectedFacets;
        },

        initPriceFacet(urlParams)
        {
            if (urlParams.priceMin || urlParams.priceMax)
            {
                const priceMin = urlParams.priceMin || "";
                const priceMax = urlParams.priceMax || "";

                this.$store.commit("setPriceFacet", { priceMin: priceMin, priceMax: priceMax });

                this.initialPriceMin = priceMin;
                this.initialPriceMax = priceMax;

                return true;
            }

            return false;
        }
    }
}
</script>