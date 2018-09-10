<template>
  <span class="more">
    <div class="more-span"></div>
    <slot></slot>
  </span>
</template>

<script>
export default {
  name: 'More',

  data () {
    return {
    }
  },

  methods: {
  }
}
</script>

<style>

.more {
  position: relative;
  z-index: 99;
}

.more .more-span {
  position: absolute;
  right: 100%;
  top: 0;
  width: 200px;
  height: 100%;
  display: none;
}

.more:hover .more-span {
  display: block;
}

.more a {
  color: #5a5e66;
}

.more .more-menu-item:hover {
  color: #EB9E05;
}

.more-button {
  cursor: pointer;
  color: #EB9E05;
  font-weight: bold;
  /*
  color: #5a5e66;
  */
}

.more .more-dropdown {
  width: 150px;
  position: absolute;
  /*
  display: none;
  */
  background-color: #f9f9f9;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  right: 0px;
  z-index: 99;

  /*
  height: 0;
  overflow: hidden;
  */
  opacity: 0;
  pointer-events: none;
  transition: all 0.5s linear;
}

.more .more-dropdown.left {
  left: 0;
}

.more .more-dropdown.right {
  right: 0;
}

.more .more-dropdown.top {
  bottom: 100%;
}

.more .more-dropdown.bottom {
  top: 100%;
}

.more:hover .more-dropdown {
  /*
  height: auto;
  overflow: visible;
  */
  opacity: 1;
  pointer-events: auto;
  transition: all 0.5s linear;
  /*
  display: block;
  */
}

.more-dropdown .more-menu-item {
  cursor: pointer;
  padding: 0px 16px;
  text-decoration: none;
  color: #5a5e66;
  display: block;
  position: relative;
  font-weight: normal;
}

.more-dropdown .more-menu-item:hover {
  background-color: #f1f1f1;
  color: #EB9E05;
  font-weight: bold;
}

.more-dropdown .more-submenu {
  min-width: 150px;
  position: absolute;
  display: none;
  background-color: #f9f9f9;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
}

.more-dropdown.left .more-submenu {
  left: 100%;
}

.more-dropdown.right .more-submenu {
  right: 100%;
}

.more-dropdown.bottom .more-submenu {
  top: 0;
}

.more-dropdown.top .more-submenu {
  bottom: 0;
}

.more-dropdown .more-menu-item:hover >.more-submenu {
  display: block;
  color: #5a5e66;
}

</style>
