<template>
  <k-field
      class="keyword-wrapper"
      :disabled="disabled"
      :help="help"
      :label="label"
      :required="required"
      :when="when"
  >
    <k-input
      theme="field"
      type="text"
      :value="value"
      @input="onInput"
    >
      <k-button
          :link="url"
          slot="icon"
          :tooltip="$t('open')"
          :class="['k-input-icon-button keywordcheck', color]"
          tabindex="-1"
          target="_blank"
          :text="scoreDyn"
      />
    </k-input>
    <div v-if="!paid" class="keywordlink">powered by <a href="https://www.seobility.net/en/?ref=kirby3-seobility-plugin" target="_blank">Seobility.net</a></div>
  </k-field>
</template>

<script>
export default {
  props: {
    disabled: Boolean,
    help: String,
    label: String,
    required: Boolean,
    when: String,
    value: String,
    score: Number,
    url: String,
    loading: { type: Boolean, default: false },
    paid: { type: Boolean, default: false },
  },

  watch: {
    hasChanges() {
      // post save or on revert
      if (!this.hasChanges) {
        this.syncContent()
      }
    }
  },

  computed: {
    hasChanges() {
      return this.$store.getters["content/hasChanges"]();
    },
    scoreDyn() {
      return this.hasChanges || this.loading ? '' : this.score;
    },
    color() {
      if (!this.score || this.hasChanges) {
        return 'white';
      }
      if (this.score <= 30) {
        return 'red';
      }
      if (this.score <= 80) {
        return 'yellow';
      }
      if (this.score > 80) {
        return 'green';
      }
    },
  },

  methods: {
    onInput(value) {
      this.$emit("input", value);
    },
    syncContent() {
      let contentId = this.$store.getters["content/id"]();
      let parts = contentId.split('?');
      let id = parts[0];
      let lang = false;
      if (parts.length > 1) {
        let queryString = new URLSearchParams(parts[1]);
        for(let pair of queryString.entries()) {
          if(pair[0] == 'language') {
            lang = pair[1];
          }
        }
      }
      this.loading = true
      this.$api.get('seobility/keywordcheck', {
        id: id,
        lang: lang
      })
      .then(response => {
        this.score = response.score
        this.url = response.url
        this.loading = false
      })
      .catch(error => {
        this.loading = false
      })
    }
  },
};
</script>

<style>
  .keyword-wrapper {

  }
  .keyword-wrapper .keywordcheck {
    display: flex;
    background: var(--color-orange-400);
    color: #fff;
    overflow: hidden;
    align-items: center;
    justify-content: center;
    font-weight: 600;
  }
  .keyword-wrapper .keywordcheck.red {
    background: var(--color-red)
  }
  .keyword-wrapper .keywordcheck.yellow {
    background: var(--color-yellow)
  }
  .keyword-wrapper .keywordcheck.green {
    background: var(--color-green)
  }
  .keyword-wrapper .keywordcheck span {
    opacity: 1;
  }
  .keyword-wrapper .keywordlink {
    padding-top: 0.25rem;
    padding-right: 0.125rem;
    text-align: right;
    font-size: var(--text-xs);
    color: var(--color-gray-600);
  }
  .keyword-wrapper .keywordlink a,
  .keyword-wrapper .keywordlink a:hover,
  .keyword-wrapper .keywordlink a:visited,
  .keyword-wrapper .keywordlink a:active {
    color: var(--color-gray-900);
    font-weight: var(--font-bold);
    text-decoration: underline;
  }
</style>
