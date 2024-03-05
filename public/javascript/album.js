var paginate = 1;
var dataExplore1 = [];
loadMoreData1(paginate);
$(window).scroll(function(){
    if($(window).scrollTop() + $(window).height() >= $(document).height()){
        paginate++;
        loadMoreData1(paginate);
    }
})

function loadMoreData1(paginate){
    $.ajax({
        url: window.location.origin +'/getDataAlbum'+ '?page='+paginate,
        type: "GET",
        dataType: "JSON",
        success: function(e){
            console.log(e)
            e.data.data.map((x)=>{
                var tanggal = x.created_at;
                var tanggalObj = new Date(tanggal);
                var tanggalFormatted = ('0' + tanggalObj.getDate()).slice(-2);
                var bulanFormatted = ('0' + (tanggalObj.getMonth() + 1)).slice(-2);
                var tahunFormatted = tanggalObj.getFullYear();
                var tanggalupload = tanggalFormatted + '-' + bulanFormatted + '-' + tahunFormatted;
                var hasilPencarian = x.likefoto.filter(function(hasil){
                    return hasil.users_id === e.idUser
                })
                if(hasilPencarian.length <= 0){
                    userlike = 0;
                } else {
                    userlike = hasilPencarian[0].users_id;
                }
                let datanya = {
                    id: x.id,
                    judul_foto: x.judul_foto,
                    deksripsi_foto: x.deksripsi_foto,
                    foto: x.lokasi_file,
                    created_at: tanggalupload,
                    Nama_Album : x.album.Nama_Album,
                    username: x.users.username,
                    foto_profil: x.users.foto_profil,
                    jml_komen: x.komenfoto_count,
                    jml_like: x.likefoto_count,
                    idUserLike: userlike,
                    useractive: e.idUser,
                }
                dataExplore1.push(datanya)
                console.log(userlike)
                console.log(e.idUser)
            })
            getExplore1()
            getExplore2()
        },
        error: function(jqXHR, textStatus, errorThrown){

        }

    })
}
//pengulangan data
const getExplore1 =()=>{
    $('#postingan-foto').html('')
    dataExplore1.map((x, i)=>{
        $('#postingan-foto').append(
            `
                    <div class="flex mt-2">
                        <div class="mt-2 flex flex-col px-2 py-4 bg-white shadow-md rounded-md">
                            <div class="mb-2">
                                <div class="ml-2 flex justify-between space-x-2">
                                        <div class="flex flex-wrap items-center space-x-2">
                                            <img src="/pic/${x.foto_profil}" alt="User Avatar"
                                                class="w-8 h-8 rounded-full">
                                            <div>
                                                <p class="text-gray-800 font-semibold">${x.username}</p>
                                                <p class="text-gray-500 text-sm">${x.created_at}</p>
                                            </div>
                                        </div>
                                        <div class="flex md:order-2 space-x-3 md:space-x-0 rtl:space-x-reverse">
                                        <button type="button" data-id="${x.id}" class="btn-bi-trash text-red-500">
                                        <span class="bi bi-trash"></span>
                                        </button>
                                    <!--Menu User-->
                                </div>
                                </div>
                            </div>
                                <div class="w-[363px] h-[192px] overflow-hidden rounded-md">
                                    <img src="postingan/${x.foto}" alt=""
                                        class="w-full transition duration-500 ease-in-out hover:scale-105">
                                </div>
                            <div class="flex flex-wrap items-center justify-between px-2 mt-2">
                                <div>
                                    <div class="flex flex-col">
                                        <div class="font-bold">
                                            ${x.judul_foto}
                                        </div>
                                        <div>
                                            ${x.deksripsi_foto}
                                        </div>
                                        <div class="text-blue-500 text-sm">
                                            ${x.Nama_Album}
                                        </div>
                                    </div>
                                </div>
                                <div>
                                <a href="/explore-detail/${x.id}">
                                <span class="bi bi-chat-left-text"></span></a>                                    
                                <small>${x.jml_komen}</small>
                                    <span class="bi ${x.idUserLike === x.useractive ? 'bi-heart-fill ' : 'bi-heart' }" onclick="likes(this, ${x.id})"></span>
                                    <small>${x.jml_like}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    
            `
        )
    })
}


//likefoto
function likes(txt, id){
    $.ajax({
        url: window.location.origin +'/likefoto',
        dataType: "JSON",
        type: "POST",
        data: {
            _token: $('input[name="_token"]').val(),
            idfoto: id
        },
        success:function(res){
            console.log(res)
            location.reload()
        },
        error:function(jqXHR, textStatus, errorThrown){
            alert('gagal')

        }
    })
}

//Hapus Foto
$(document).on('click', '.btn-bi-trash', function() {
    console.log('Tombol Hapus Diklik');
    var id = $(this).data('id');

    // Show loading spinner or change appearance immediately
    $('#elemen-foto-' + id).addClass('deleting');

    if (confirm('Apakah Anda Yakin Ingin Menghapus Postingan Ini?')) {
        deletefoto(id);
    }

    function deletefoto(id) {
        $.ajax({
            url: '/deletefoto/' + id,
            dataType: "JSON",
            type: "DELETE",
            data: {
                _token: $('input[name="_token"]').val(),
                id: id
            },
            success: function(res) {
                if (res.success) {
                    // Hapus elemen postingan dari tampilan
                    $('#elemen-foto-' + id).remove();
                    // Refresh the page
                    location.reload();
                } else {
                    alert('Gagal Menghapus Postingan Ini');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                alert('Gagal Menghapus Postingan Ini');
            },
            complete: function() {
                // Remove loading spinner or revert appearance
                $('#elemen-foto-' + id).removeClass('deleting');
            }
        });
    }
});