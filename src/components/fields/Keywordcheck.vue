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
      if (this.score <= 50) {
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
</style>
