<?php
defined('BASEPATH') OR exit('No direct script access allowed');

#[AllowDynamicProperties]
class Produk extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Produk_model');
    }

    public function index() {
        // $data['produk'] = $this->Produk_model->get_all_produk('bisa dijual');
        $data['produk'] = $this->Produk_model->get_all_produk(); 
        $this->load->view('produk_list', $data);
    }

public function sync_api() {
        // 1. SET WAKTU
        date_default_timezone_set('Asia/Jakarta');

        $d = date('d'); 
        $m = date('m'); 
        $y = date('y'); 
        $H = date('H'); 

        // 2. CREDENTIALS
        $username = "tesprogrammer{$d}{$m}{$y}C{$H}";
        $pass_str = "bisacoding-{$d}-{$m}-{$y}"; 
        $password = md5($pass_str);

        // 3. CURL REQUEST
        $postData = [
            'username' => $username,
            'password' => $password
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://recruitment.fastprint.co.id/tes/api_tes_programmer");
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        
        $cookie_file = sys_get_temp_dir() . '/cookie_fastprint.txt';
        curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
        curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        $response = curl_exec($ch);
        
        if(curl_errno($ch)){
            $this->session->set_flashdata('error', 'Koneksi Gagal: ' . curl_error($ch));
            redirect('produk');
            return;
        }

        curl_close($ch);
        $json = json_decode($response, true);

        if (isset($json['data']) && !empty($json['data'])) {
            
            foreach ($json['data'] as $item) {
                $cat_id = $this->Produk_model->get_or_create_kategori($item['kategori']);
                $stat_id = $this->Produk_model->get_or_create_status($item['status']);

                $data_produk = [
                    'id_produk'     => $item['id_produk'],
                    'nama_produk'   => $item['nama_produk'],
                    'harga'         => $item['harga'],
                    'kategori_id'   => $cat_id,
                    'status_id'     => $stat_id
                ];
                $this->Produk_model->upsert_produk($data_produk);
            }

            $this->session->set_flashdata('msg', 'Sync Sukses! Data masuk.');
        } else {
            // DEBUGGING ERROR
            $pesan = isset($json['ket']) ? $json['ket'] : 'Respon API tidak dikenal.';
            $debug_info = " (User: $username)";
            $this->session->set_flashdata('error', 'API Menolak: ' . $pesan . $debug_info);
        }
        
        redirect('produk');
    }

    // Form Tambah
    public function tambah() {
        $this->form_validation->set_rules('nama_produk', 'Nama', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['kategori'] = $this->db->get('kategori')->result();
            $data['status'] = $this->db->get('status')->result();
            $this->load->view('produk_form', $data);
        } else {
            $this->Produk_model->insert([
                'nama_produk' => $this->input->post('nama_produk'),
                'harga'       => $this->input->post('harga'),
                'kategori_id' => $this->input->post('kategori_id'),
                'status_id'   => $this->input->post('status_id')
            ]);
            $this->session->set_flashdata('msg', 'Data disimpan!');
            redirect('produk');
        }
    }

    // Form Edit
    public function edit($id) {
        $this->form_validation->set_rules('nama_produk', 'Nama', 'required');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['produk'] = $this->Produk_model->get_by_id($id);
            $data['kategori'] = $this->db->get('kategori')->result();
            $data['status'] = $this->db->get('status')->result();
            
            if(!$data['produk']) show_404();
            
            $this->load->view('produk_form', $data);
        } else {
            $this->Produk_model->update($id, [
                'nama_produk' => $this->input->post('nama_produk'),
                'harga'       => $this->input->post('harga'),
                'kategori_id' => $this->input->post('kategori_id'),
                'status_id'   => $this->input->post('status_id')
            ]);
            $this->session->set_flashdata('msg', 'Data diupdate!');
            redirect('produk');
        }
    }

    public function hapus($id) {
        $this->Produk_model->delete($id);
        $this->session->set_flashdata('msg', 'Data dihapus!');
        redirect('produk');
    }
}