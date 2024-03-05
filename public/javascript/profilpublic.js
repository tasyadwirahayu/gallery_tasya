var segmentTerakhir = window.location.href.split("/").pop();

$.getJSON(window.location.origin +'/profil_public/getDataPin/'+segmentTerakhir, function(res){
    console.log(res)
    $('#username').html(res.dataUser.username)
    $('#imageuser').prop('src', '/fotoprofile/'+res.dataUser.pictures)
    $('#follower').html(res.jumlahFollower[0].jmlfollower+' Pengikut')
    $('#follow').html(res.jumlahFollow[0].jmlfollow+' Mengikuti')
    if(res.dataUserActive == res.dataUser.id){
    $('#tombolfollow').html('')
    } else {
        if(res.dataFollow == null){
          $('#tombolfollow').html('<button class="px-4 mt-4 text-white bg-black rounded-full" onclick="ikuti(this, '+ res.dataUser.id +')">Ikuti</button>')
        } else {
          $('#tombolfollow').html('<button class="px-4 mt-4 border-black bg-white text-black rounded-full" onclick="ikuti(this, '+ res.dataUser.id +')">Mengikuti</button>')
        }
    }
})

//Ikuti
function ikuti(txt, id){
    $.ajax({
        url: window.location.origin +'/explore-detail/ikuti',
        type: "POST",
        dataType: "JSON",
        data: {
            idfollow: id,
            _token: $('input[name="_token"]').val()
        },
        success: function(res){
            location.reload()
        },
        error: function(jqXHR, textStatus, errorThrown){
            alert('Gagal')
        }
    })
}


//postingan bawah
var paginate = 1;
var dataExplore = [];
loadMoreData(paginate);
$(window).scroll(function(){
    if($(window).scrollTop() + $(window).height() >= $(document).height()){
        paginate++;
        loadMoreData(paginate);
    }
})
function loadMoreData(paginate){
    let user_id = $('#input-user_id').val();
    $.ajax({
        url: window.location.origin +'/getDataPublic/'+ user_id + '?page='+paginate,
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
                    Nama_Album : x.album ? x.album.Nama_Album :'-',
                    username: x.users.username,
                    foto_profil: x.users.foto_profil,
                    jml_komen: x.komenfoto_count,
                    jml_like: x.likefoto_count,
                    idUserLike: userlike,
                    useractive: e.idUser,
                }
                dataExplore.push(datanya)
                console.log(userlike)
                console.log(e.idUser)
            })
            getExplore()
        },
        error: function(jqXHR, textStatus, errorThrown){

        }

    })
}
//pengulangan data
const getExplore =()=>{
    $('#publicfoto').html('')
    dataExplore.map((x, i)=>{
        $('#publicfoto').append(
            `
                    <div class="flex mt-2">
                        <div class="mt-2 flex flex-col px-2 py-4 bg-white shadow-md rounded-md">
                            <div class="mb-2">
                                <div class="ml-2 flex justify-between space-x-2">
                                    <a href="/profil_public">
                                        <div class="flex flex-wrap items-center space-x-2">
                                            <img src="/pic/${x.foto_profil}" alt="User Avatar"
                                                class="w-8 h-8 rounded-full">
                                            <div>
                                                <p class="text-gray-800 font-semibold">${x.username}</p>
                                                <p class="text-gray-500 text-sm">${x.created_at}</p>
                                            </div>
                                        </div>
                                    </a>
                                    
                                </div>
                            </div>
                                <div class="w-[363px] h-[192px] overflow-hidden rounded-md">
                                    <img src="/postingan/${x.foto}" alt=""
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
                                <span class="bi bi-chat-left-text"></span></a>                                    <small>${x.jml_komen}</small>
                                    <span class="bi ${x.idUserLike === x.useractive ? 'bi-heart-fill' : 'bi-heart' }" onclick="likes(this, ${x.id})"></span>
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