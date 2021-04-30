<template>
  <main class="font-sans-serif h-screen flex flex-col">
    <header
      class="bg-yellow-300 border-b dark:border-gray-500 p-4 flex items-center justify-between"
    >
      <h1 class="text-lg font-bold">Svelte</h1>
      <a
        href="https://github.com/adamdburton/svelte"
        target="_blank"
        class="flex items-center px-3 py-1 rounded-full hover:bg-black hover:text-yellow-300"
      >
        <svg
          class="w-6 h-6"
          fill="currentColor"
          viewBox="0 0 20 20"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            fill-rule="evenodd"
            d="M12.316 3.051a1 1 0 01.633 1.265l-4 12a1 1 0 11-1.898-.632l4-12a1 1 0 011.265-.633zM5.707 6.293a1 1 0 010 1.414L3.414 10l2.293 2.293a1 1 0 11-1.414 1.414l-3-3a1 1 0 010-1.414l3-3a1 1 0 011.414 0zm8.586 0a1 1 0 011.414 0l3 3a1 1 0 010 1.414l-3 3a1 1 0 11-1.414-1.414L16.586 10l-2.293-2.293a1 1 0 010-1.414z"
            clip-rule="evenodd"
          ></path>
        </svg>
        <div class="ml-2">View on GitHub</div>
      </a>
    </header>
    <div class="lg:flex flex-1 overflow-auto lg:overflow-hidden">
      <aside
        class="lg:w-1/2 border-r dark:border-gray-500 bg-gray-100 h-1/2-screen lg:h-screen lg:h-auto relative"
      >
        <a-scene
          embedded
          device-orientation-permission-ui
          vr-mode-ui="enabled: false"
          cursor="rayOrigin: mouse"
          shadow="type: pcfsoft"
        >
          <a-assets>
            <a-asset-item
              id="island-obj"
              src="/assets/island.obj"
            ></a-asset-item>
            <a-asset-item
              id="island-mtl"
              src="/assets/island.mtl"
            ></a-asset-item>

            <a-asset-item
              id="turbine-obj"
              src="/assets/SM_WindTurbine_02.obj"
            ></a-asset-item>
            <a-asset-item
              id="turbine-mtl"
              src="/assets/SM_WindTurbine_02.mtl"
            ></a-asset-item>

            <a-asset-item id="drone-obj" src="/assets/model.obj"></a-asset-item>
            <a-asset-item
              id="drone-mtl"
              src="/assets/materials.mtl"
            ></a-asset-item>
          </a-assets>

          <a-sky color="#ECECEC"></a-sky>
          <a-ocean
            width="50"
            depth="50"
            density="30"
            position="0 -0.1 0"
            shadow
          ></a-ocean>

          <a-entity light="type: ambient; intensity: 0.6;"></a-entity>

          <a-entity
            animation__rotation="property: rotation; from: 45 0 0; to: 45 360 0; loop: true; dur: 60000; dir: reverse; loop: true; easing: linear"
          >
            <a-entity
              light="type: directional; castShadow: true; shadowCameraBottom: -15; shadowCameraLeft: -15; shadowCameraRight: 15; shadowCameraTop: 15; shadowMapHeight: 1024; shadowMapWidth: 1024; intensity: 0.7;"
              position="30 50 -50"
            ></a-entity>
          </a-entity>

          <a-obj-model
            src="#island-obj"
            mtl="#island-mtl"
            scale="4 4 4"
            rotation="0 90 0"
            shadow
          ></a-obj-model>

          <a-obj-model
            v-for="turbine in turbines"
            :key="turbine.id"
            src="#turbine-obj"
            mtl="#turbine-mtl"
            scale="0.075 0.075 0.075"
            :position="turbine.position.join(' ')"
            :rotation="turbine.rotation.join(' ')"
            shadow
          ></a-obj-model>

          <a-obj-model
            src="#drone-obj"
            mtl="#drone-mtl"
            animation__position="property: position; from: 10 10 -10; to: -4 8 15; dur: 10000; loop: true; dir: alternate; easing: linear"
            shadow
          ></a-obj-model>

          <a-obj-model
            src="#drone-obj"
            mtl="#drone-mtl"
            animation__position="property: position; from: -10 11 10; to: 20 8 5; dur: 10000; delay: 5000; loop: true; dir: alternate; easing: linear"
            shadow
          ></a-obj-model>

          <a-entity
            ref="rig"
            id="camera-rig"
            :animation__position="cameraPosition"
            :animation__rotation="cameraRotation"
          >
            <a-entity
              id="camera"
              ref="camera"
              :camera="camera"
              :animation__position="cameraTrackPosition"
              rotation="-36 45 0"
            >
              <a-entity cursor />
            </a-entity>
          </a-entity>
        </a-scene>
      </aside>
      <main class="lg:w-1/2 overflow-auto relative dark:text-white">
        <div v-if="selectedTurbine" class="dark:bg-gray-700">
          <div
            class="lg:flex items-center justify-between px-2 py-1 border-b dark:border-gray-500 sticky top-0 bg-white dark:bg-gray-700"
          >
            <h1
              class="self-start flex items-center justify-center cursor-pointer hover:bg-gray-100 rounded-full p-1 pr-2 lg:justify-left mb-4 lg:mb-0 font-bold dark:hover:text-gray-700"
              @click="clearTurbine"
            >
              <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
              <div class="ml-1">Turbine #{{ selectedTurbine.id }}</div>
            </h1>
            <div class="flex items-center justify-center lg:justify-end text-xs gap-x-2">
              <div class="text-gray-500 dark:text-gray-300 px-2 py-0.5">Filter components:</div>
              <div
                class="rounded-full px-2 py-0.5 cursor-pointer text-center hover:bg-gray-200 dark:hover:text-gray-700"
                @click="partFilter = ''"
                :class="partFilter === '' ? 'bg-yellow-300 dark:text-gray-700' : ''"
              >
                All Components
              </div>
              <div
                class="rounded-full px-2 py-0.5 cursor-pointer text-center hover:bg-gray-200 dark:hover:text-gray-700"
                @click="partFilter = 'issues'"
                :class="partFilter === 'issues' ? 'bg-yellow-300 dark:text-gray-700' : ''"
              >
                Only Components with issues
              </div>
            </div>
          </div>

          <table class="text-left w-full">
            <thead>
              <tr class="text-gray-500 dark:text-gray-300 border-b dark:border-gray-500">
                <th class="p-2 bg-gray-100 dark:bg-gray-600 font-normal">Component</th>
                <th class="p-2 bg-gray-100 dark:bg-gray-600 font-normal">Issues</th>
                <th class="p-2 bg-gray-100 dark:bg-gray-600 font-normal text-right">Passing</th>
              </tr>
            </thead>
            <tbody>
              <tr
                v-for="inspection in filteredParts"
                :key="inspection.id"
                class="border-b dark:border-gray-500"
              >
                <td class="p-2">
                  <div
                    class="inline-block px-1.5 py-0.5 bg-yellow-300 rounded-full text-sm dark:text-gray-700"
                  >
                    #{{ inspection.id }}
                  </div>
                </td>
                <td class="p-2">
                  <div v-if="inspection.issues.length > 0">
                    {{ inspection.issues.join(" and ") }}
                  </div>
                  <div v-else class="text-gray-500">N/A</div>
                </td>
                <td class="p-2 flex justify-end">
                  <svg
                    v-if="inspection.issues.length === 0"
                    class="text-green-400 w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                  <svg
                    v-else
                    class="text-red-400 w-6 h-6"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      fill-rule="evenodd"
                      d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                      clip-rule="evenodd"
                    ></path>
                  </svg>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div v-else class="flex flex-col h-full dark:bg-gray-700">
          <div class="lg:flex items-center justify-between p-2 lg:border-b dark:border-gray-500">
            <h1
              class="font-bold self-start mb-4 lg:mb-0 text-center lg:text-left"
            >
              Turbines
            </h1>
            <div
              class="flex items-center justify-center lg:justify-end text-xs gap-x-2"
            >
              <div class="text-gray-500 dark:text-gray-300 px-2 py-0.5">Filter turbines:</div>
              <div
                class="rounded-full px-2 py-0.5 cursor-pointer hover:bg-gray-200 dark:hover:text-gray-700"
                @click="turbineFilter = ''"
                :class="turbineFilter === '' ? 'bg-yellow-300 dark:text-gray-700' : ''"
              >
                All Turbines
              </div>
              <div
                class="rounded-full px-2 py-0.5 cursor-pointer hover:bg-gray-200 dark:hover:text-gray-700"
                @click="turbineFilter = 'issues'"
                :class="turbineFilter === 'issues' ? 'bg-yellow-300 dark:text-gray-700' : ''"
              >
                Only Turbines with issues
              </div>
            </div>
          </div>

          <div
            class="flex flex-wrap gap-4 lg:gap-8 items-center justify-center flex-1"
          >
            <div
              v-for="turbine in filteredTurbines"
              :key="turbine.id"
              class="p-4 bg-white dark:bg-gray-700 rounded-lg cursor-pointer hover:bg-gray-100 dark:hover:bg-gray-500"
              @click="setTurbine(turbine)"
            >
              <h2 class="font-bold">Turbine #{{ turbine.id }}</h2>
              <div class="flex justify-center p-4">
                <svg
                  v-if="
                    turbine.parts.filter((part) => part.issues.length !== 0)
                      .length === 0
                  "
                  class="text-green-400 w-12 h-12"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
                <svg
                  v-else
                  class="text-red-400 w-12 h-12 animate-bounce"
                  fill="currentColor"
                  viewBox="0 0 20 20"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <path
                    fill-rule="evenodd"
                    d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z"
                    clip-rule="evenodd"
                  ></path>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </main>
</template>


<script>
export default {
  data: () => ({
    turbines: [],
    selectedTurbineId: null,
    turbineFilter: "",
    partFilter: "",
  }),
  methods: {
    setTurbine(turbine) {
      this.selectedTurbineId = turbine.id;
      this.partFilter = "";
    },
    clearTurbine() {
      this.selectedTurbineId = null;
    },
  },
  computed: {
    cameraRotation() {
      return this.selectedTurbine !== null
        ? "property: rotation; from: 0 0 0; to: 0 360 0; loop: true; dur: 10000; loop: true; easing: linear;"
        : "property: rotation; to: 0 0 0; dur: 1000; easing: linear;";
    },
    selectedTurbine() {
      return this.selectedTurbineId !== null
        ? this.turbines.find((turbine) => turbine.id === this.selectedTurbineId)
        : null;
    },
    cameraPosition() {
      return this.selectedTurbine !== null
        ? "property: position; to: " +
            this.selectedTurbine.position.join(" ") +
            "; dur: 1000; easing: linear;"
        : "property: position; to: -3.5 0 -3.5; dur: 1000; easing: linear;";
    },
    cameraTrackPosition() {
      return this.selectedTurbine !== null
        ? "property: position; to: 30 31.380 30; dur: 1000; easing: linear;"
        : "property: position; to: 50 51.380 50; dur: 1000; easing: linear;";
    },
    filteredParts() {
      return this.selectedTurbine !== null
        ? this.partFilter === "issues"
          ? this.selectedTurbine.parts.filter(
              (inspection) => inspection.issues.length > 0
            )
          : this.selectedTurbine.parts
        : [];
    },
    filteredTurbines() {
      return this.turbineFilter === "issues"
        ? this.turbines.filter(
            (turbine) =>
              turbine.parts.filter((part) => part.issues.length > 0).length > 0
          )
        : this.turbines;
    },
    camera() {
      return {
        fov: 10,
        zoom: 1,
      };
    },
  },
  mounted() {
    axios
      .get("/api/data")
      .then((data) => (this.turbines = data.data))
      .catch((err) => alert(err));
  },
};
</script>
