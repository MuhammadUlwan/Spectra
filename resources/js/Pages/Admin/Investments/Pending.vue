<script setup>
defineProps({
  investments: Array
})
</script>

<template>
  <div>
    <h1 class="text-xl font-bold">Pending Investments</h1>
    <p v-if="!investments.length">Belum ada investasi pending</p>

    <table v-else class="table-auto w-full mt-4 border">
      <thead>
        <tr>
          <th class="border px-2">Investor</th>
          <th class="border px-2">Paket</th>
          <th class="border px-2">Jumlah</th>
          <th class="border px-2">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="inv in investments" :key="inv.id">
          <td class="border px-2">{{ inv.user.name }}</td>
          <td class="border px-2">{{ inv.package.name }}</td>
          <td class="border px-2">{{ inv.amount }}</td>
          <td class="border px-2">
            <button class="bg-green-500 text-white px-2 py-1 rounded">Validasi</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
