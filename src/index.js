import Keywordcheck from "./components/fields/Keywordcheck.vue";
import SerpRanking from "./components/fields/SerpRanking.vue";
import TermSuggestion from "./components/fields/TermSuggestion.vue";

panel.plugin("bnomei/seobility", {
  fields: {
    keywordcheck: Keywordcheck,
    ranking: SerpRanking,
    termsuggestion: TermSuggestion,
  }
});
