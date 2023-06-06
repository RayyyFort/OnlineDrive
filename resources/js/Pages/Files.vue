<script>
  import MyHeader from "../Layouts/header.vue";

  export default{
    props:{
      logged:String,
    },
    components: {
      MyHeader
    }
  }
</script>

<template>
  <div style="height: 100%; width: 100%; display: flex; flex-direction: column;">
    <MyHeader :logged="this.logged"/>
    <main style="display: flex; flex-direction: column; height: 100%; border: 1px solid white;">
      <h1 style="margin: auto; margin-top: 10px; margin-bottom: 10px;">Your Files</h1>
      <div style="max-width: 80%; max-height: 90%; width:100%; height: 100%; margin: auto; border: 1px solid black;">
        <table>
          <thead>
            <th>
              PATH TO THE CURRENT FOLDER
            </th>
          </thead>
          <tbody>
            <tr>
              <td>
                <p>test file</p>
              </td>
            </tr>
            <tr>
              <td>
                <p>test file</p>
              </td>
            </tr>
            <tr>
              <td>
                <p>test file</p>
              </td>
            </tr>
            <tr>
              <td>
                <p>test file</p>
              </td>
            </tr>
            <tr>
              <td>
                <p>test file</p>
              </td>
            </tr>
            <tr>
              <td>
                <p>test file</p>
              </td>
            </tr>
            <tr>
              <td>
                <p>test file</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>