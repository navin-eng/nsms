<?php

namespace App\Helpers;

class NepalLocations
{
    /**
     * Get all 7 Provinces with their districts and local levels (Municipalities/Rural Municipalities)
     */
    public static function getHierarchy(): array
    {
        return [
            'Koshi Province' => [
                'Bhojpur' => [
                    'Bhojpur Municipality', 'Shadanand Municipality', 'Hatuwagadhi Rural Municipality', 
                    'Ramprasad Rai Rural Municipality', 'Aamchok Rural Municipality', 'Tyamke Maiyum Rural Municipality', 
                    'Arun Rural Municipality', 'Pauwadungma Rural Municipality', 'Salpasilichho Rural Municipality'
                ],
                'Dhankuta' => [
                    'Dhankuta Municipality', 'Pakhribas Municipality', 'Mahalaxmi Municipality', 
                    'Sangurigadhi Rural Municipality', 'Khalsa Chhintang Sahidbhumi Rural Municipality', 
                    'Chhathar Jorpati Rural Municipality', 'Chaubise Rural Municipality'
                ],
                'Ilam' => [
                    'Ilam Municipality', 'Deumai Municipality', 'Mai Municipality', 'Suryodaya Municipality', 
                    'Phakphokthum Rural Municipality', 'Maijogmai Rural Municipality', 'Chulachuli Rural Municipality', 
                    'Rong Rural Municipality', 'Mangsebung Rural Municipality', 'Sandakpur Rural Municipality'
                ],
                'Jhapa' => [
                    'Bhadrapur Municipality', 'Birtamod Municipality', 'Damak Municipality', 'Kankai Municipality', 
                    'Mechinagar Municipality', 'Arjundhara Municipality', 'Shivasatakshi Municipality', 'Gauradaha Municipality', 
                    'Barhadashi Rural Municipality', 'Jhapa Rural Municipality', 'Kachankawal Rural Municipality', 
                    'Kamal Rural Municipality', 'Haldibari Rural Municipality', 'Gauriganj Rural Municipality'
                ],
                'Khotang' => [
                    'Diktel Rupakot Majhuwagadhi Municipality', 'Halesi Tuwachung Municipality', 'Khotehang Rural Municipality', 
                    'Diprung Chuichumma Rural Municipality', 'Aiselukharka Rural Municipality', 'Jantedhunga Rural Municipality', 
                    'Kepilasgadhi Rural Municipality', 'Barahapokhari Rural Municipality', 'Rawabesi Rural Municipality', 'Sakela Rural Municipality'
                ],
                'Morang' => [
                    'Biratnagar Metropolitan City', 'Belbari Municipality', 'Letang Municipality', 'Pathari Sanischare Municipality', 
                    'Rangeli Municipality', 'Ratuwamai Municipality', 'Sunbarshi Municipality', 'Sundar Haraicha Municipality', 
                    'Urlabari Municipality', 'Budhiganga Rural Municipality', 'Dhanpalthan Rural Municipality', 
                    'Gramthan Rural Municipality', 'Jahada Rural Municipality', 'Kanepokhari Rural Municipality', 
                    'Katahari Rural Municipality', 'Kerabari Rural Municipality', 'Miklajung Rural Municipality'
                ],
                'Okhaldhunga' => [
                    'Siddhicharan Municipality', 'Khijidemba Rural Municipality', 'Champadevi Rural Municipality', 
                    'Chisankhugadhi Rural Municipality', 'Manebhanjyang Rural Municipality', 'Molung Rural Municipality', 
                    'Likhu Rural Municipality', 'Sunkoshi Rural Municipality'
                ],
                'Panchthar' => [
                    'Phidim Municipality', 'Phalelung Rural Municipality', 'Phalgunanda Rural Municipality', 
                    'Hilihang Rural Municipality', 'Kummayak Rural Municipality', 'Miklajung Rural Municipality', 
                    'Tumbewa Rural Municipality', 'Yangwarak Rural Municipality'
                ],
                'Sankhuwasabha' => [
                    'Khandbari Municipality', 'Chainpur Municipality', 'Dharmadevi Municipality', 'Madi Municipality', 
                    'Panchakhapan Municipality', 'Bhotkhola Rural Municipality', 'Chichila Rural Municipality', 
                    'Makalu Rural Municipality', 'Sabha Pokhari Rural Municipality', 'Silichong Rural Municipality'
                ],
                'Solukhumbu' => [
                    'Solududhkunda Municipality', 'Dudhakaushika Rural Municipality', 'Nechasalyan Rural Municipality', 
                    'Dudhkoshi Rural Municipality', 'Maha Kulung Rural Municipality', 'Sotang Rural Municipality', 
                    'Likhu Pike Rural Municipality', 'Khumbu Pasang Lhamu Rural Municipality'
                ],
                'Sunsari' => [
                    'Dharan Sub-Metropolitan City', 'Itahari Sub-Metropolitan City', 'Inaruwa Municipality', 
                    'Duhabi Municipality', 'Ramdhuni Municipality', 'Barahachhetra Municipality', 
                    'Dewanganj Rural Municipality', 'Koshi Rural Municipality', 'Gadhi Rural Municipality', 
                    'Barju Rural Municipality', 'Bhokraha Narsing Rural Municipality', 'Harinagar Rural Municipality'
                ],
                'Taplejung' => [
                    'Phungling Municipality', 'Aathrai Tribeni Rural Municipality', 'Sidingwa Rural Municipality', 
                    'Phaktanglung Rural Municipality', 'Miklakhola Rural Municipality', 'Meringden Rural Municipality', 
                    'Maiwakhola Rural Municipality', 'Pathibhara Yangwarak Rural Municipality', 'Sirijangha Rural Municipality'
                ],
                'Terhathum' => [
                    'Myanglung Municipality', 'Laligurans Municipality', 'Aathrai Rural Municipality', 
                    'Chhathar Rural Municipality', 'Phedap Rural Municipality', 'Menchayam Rural Municipality'
                ],
                'Udayapur' => [
                    'Triyuga Municipality', 'Katari Municipality', 'Chaudandigadhi Municipality', 'Belaka Municipality', 
                    'Udayapurgadhi Rural Municipality', 'Taapli Rural Municipality', 'Rautamai Rural Municipality', 
                    'Limchungbung Rural Municipality'
                ]
            ],
            'Madhesh Province' => [
                'Saptari' => ['Rajbiraj Municipality', 'Kanchanrup Municipality', 'Dakneshwori Municipality', 'Bodebarsain Municipality', 'Khadak Municipality', 'Shambhunath Municipality', 'Surunga Municipality', 'Hanumannagar Kankalini Municipality', 'Saptakoshi Municipality', 'Agnisair Krishna Savaran Rural Municipality', 'Balan-Bihul Rural Municipality', 'Bishnupur Rural Municipality', 'Chhinnamasta Rural Municipality', 'Mahadeva Rural Municipality', 'Rupani Rural Municipality', 'Tilathi Koiladi Rural Municipality', 'Tirhut Rural Municipality'],
                'Siraha' => ['Lahan Municipality', 'Siraha Municipality', 'Golbazar Municipality', 'Mirchaiya Municipality', 'Kalyanpur Municipality', 'Dhangadhimai Municipality', 'Sukhipur Municipality', 'Karjanha Municipality', 'Aurahi Rural Municipality', 'Bariyarpatti Rural Municipality', 'Bhagawanpur Rural Municipality', 'Bishnupur Rural Municipality', 'Laxmipur Patari Rural Municipality', 'Naraha Rural Municipality', 'Navarajpur Rural Municipality', 'Sakhuwanankarkatti Rural Municipality', 'Arnama Rural Municipality'],
                'Dhanusha' => ['Janakpurdham Sub-Metropolitan City', 'Chhireshwarnath Municipality', 'Ganeshman Charnath Municipality', 'Dhanusadham Municipality', 'Nagarain Municipality', 'Bideha Municipality', 'Mithila Municipality', 'Shahidnagar Municipality', 'Sabaila Municipality', 'Kamala Municipality', 'Mithila Bihari Municipality', 'Hansapur Municipality', 'Aurahi Rural Municipality', 'Bateshwar Rural Municipality', 'Janaknandani Rural Municipality', 'Mukhiyapatti Musaharniya Rural Municipality', 'Lakshminiya Rural Municipality', 'Dhanauji Rural Municipality'],
                'Mahottari' => ['Jaleshwor Municipality', 'Bardibas Municipality', 'Gaushala Municipality', 'Loharpatti Municipality', 'Ramgopalpur Municipality', 'Manara Shisawa Municipality', 'Matihani Municipality', 'Bhangaha Municipality', 'Balwa Municipality', 'Aurahi Municipality', 'Pipra Rural Municipality', 'Samsi Rural Municipality', 'Sonama Rural Municipality', 'Ekdara Rural Municipality', 'Mahottari Rural Municipality'],
                'Sarlahi' => ['Malangwa Municipality', 'Harion Municipality', 'Ishworpur Municipality', 'Lalbandi Municipality', 'Barahathawa Municipality', 'Godaita Municipality', 'Balara Municipality', 'Bagmati Municipality', 'Kabilasi Municipality', 'Hariwan Municipality', 'Chakraghatta Rural Municipality', 'Chandranagar Rural Municipality', 'Dhankaul Rural Municipality', 'Brahmapuri Rural Municipality', 'Ramnagar Rural Municipality', 'Kaudena Rural Municipality', 'Parsa Rural Municipality', 'Bishnu Rural Municipality', 'Basbariya Rural Municipality'],
                'Rautahat' => ['Gaur Municipality', 'Chandrapur Municipality', 'Garuda Municipality', 'Gujara Municipality', 'Brindaban Municipality', 'Dewahi Gonahi Municipality', 'Gadhimai Municipality', 'Madhav Narayan Municipality', 'Maulapur Municipality', 'Phatuwa Bijayapur Municipality', 'Ishnath Municipality', 'Paroha Municipality', 'Rajpur Municipality', 'Rajdevi Municipality', 'Katahariya Municipality', 'Durga Bhagwati Rural Municipality', 'Yamunamai Rural Municipality'],
                'Bara' => ['Kalaiya Sub-Metropolitan City', 'Jeetpursimara Sub-Metropolitan City', 'Kolhabi Municipality', 'Nijgadh Municipality', 'Mahagadhimai Municipality', 'Simraungadh Municipality', 'Pacharauta Municipality', 'Pheta Rural Municipality', 'Bishrampur Rural Municipality', 'Prasauni Rural Municipality', 'Adarsh Kotwal Rural Municipality', 'Karaiyamai Rural Municipality', 'Devtal Rural Municipality', 'Suwarna Rural Municipality', 'Baragadhi Rural Municipality'],
                'Parsa' => ['Birgunj Metropolitan City', 'Pokhariya Municipality', 'Bahudaramai Municipality', 'Parsagadhi Municipality', 'Bindabasini Rural Municipality', 'Dhobini Rural Municipality', 'Chhipaharmai Rural Municipality', 'Jagarnathpur Rural Municipality', 'Jirabhawani Rural Municipality', 'Kalikamai Rural Municipality', 'Pakaha Mainpur Rural Municipality', 'Paterwa Sugauli Rural Municipality', 'Sakhuwa Prasauni Rural Municipality', 'Thori Rural Municipality']
            ],
            'Bagmati Province' => [
                'Bhaktapur' => ['Bhaktapur Municipality', 'Madhyapur Thimi Municipality', 'Suryabinayak Municipality', 'Changunarayan Municipality'],
                'Chitwan' => ['Bharatpur Metropolitan City', 'Ratnanagar Municipality', 'Khairhani Municipality', 'Madi Municipality', 'Rapti Municipality', 'Kalika Municipality', 'Ichhyakamana Rural Municipality'],
                'Dhading' => ['Nilkantha Municipality', 'Dhunibeshi Municipality', 'Gajuri Rural Municipality', 'Galchhi Rural Municipality', 'Benighat Rorang Rural Municipality', 'Siddhalekh Rural Municipality', 'Tripurasundari Rural Municipality', 'Gangajamuna Rural Municipality', 'Jwalamukhi Rural Municipality', 'Thakre Rural Municipality', 'Netrawati Dabjong Rural Municipality', 'Rubi Valley Rural Municipality', 'Khaniyabas Rural Municipality'],
                'Dolakha' => ['Bhimeshwor Municipality', 'Jiri Municipality', 'Kalinchok Rural Municipality', 'Gaurishankar Rural Municipality', 'Bigu Rural Municipality', 'Baiteshwor Rural Municipality', 'Sailung Rural Municipality', 'Melung Rural Municipality', 'Tamakoshi Rural Municipality'],
                'Kathmandu' => ['Kathmandu Metropolitan City', 'Kirtipur Municipality', 'Budhanilkantha Municipality', 'Chandragiri Municipality', 'Tokha Municipality', 'Tarakeshwor Municipality', 'Nagarjun Municipality', 'Gokarneshwor Municipality', 'Kageshwori Manohara Municipality', 'Dakshinkali Municipality', 'Shankharapur Municipality'],
                'Kavrepalanchok' => ['Dhulikhel Municipality', 'Banepa Municipality', 'Panauti Municipality', 'Panchkhal Municipality', 'Namobuddha Municipality', 'Mandandeupur Municipality', 'Roshi Rural Municipality', 'Temal Rural Municipality', 'Bhumlu Rural Municipality', 'Mahabharat Rural Municipality', 'Chaunrideupur Rural Municipality', 'Khanikhola Rural Municipality', 'Bethanchok Rural Municipality'],
                'Lalitpur' => ['Lalitpur Metropolitan City', 'Godawari Municipality', 'Mahalaxmi Municipality', 'Konjyosom Rural Municipality', 'Bagmati Rural Municipality', 'Mahankal Rural Municipality'],
                'Makwanpur' => ['Hetauda Sub-Metropolitan City', 'Thaha Municipality', 'Bhimphedi Rural Municipality', 'Makawanpurgadhi Rural Municipality', 'Manahari Rural Municipality', 'Raksirang Rural Municipality', 'Bakaiya Rural Municipality', 'Bagmati Rural Municipality', 'Kailash Rural Municipality', 'Indrasarowar Rural Municipality'],
                'Nuwakot' => ['Bidur Municipality', 'Belkotgadhi Municipality', 'Kakani Rural Municipality', 'Panchakanya Rural Municipality', 'Likhu Rural Municipality', 'Dupcheshwar Rural Municipality', 'Shivapuri Rural Municipality', 'Tadi Rural Municipality', 'Suryagadhi Rural Municipality', 'Tarkeshwar Rural Municipality', 'Kispang Rural Municipality', 'Myagang Rural Municipality'],
                'Ramechhap' => ['Manthali Municipality', 'Ramechhap Municipality', 'Umakunda Rural Municipality', 'Khandadevi Rural Municipality', 'Gokulganga Rural Municipality', 'Doramba Rural Municipality', 'Likhu Tamakoshi Rural Municipality', 'Sunapati Rural Municipality'],
                'Rasuwa' => ['Uttargaya Rural Municipality', 'Kalika Rural Municipality', 'Gosaikunda Rural Municipality', 'Naukunda Rural Municipality', 'Amachodingmo Rural Municipality'],
                'Sindhuli' => ['Kamalamai Municipality', 'Dudhauli Municipality', 'Golanjor Rural Municipality', 'Ghyanglekh Rural Municipality', 'Teenpatan Rural Municipality', 'Phikkal Rural Municipality', 'Marin Rural Municipality', 'Sunkoshi Rural Municipality', 'Hariharpurgadhi Rural Municipality'],
                'Sindhupalchok' => ['Chautara Sangachowkgadhi Municipality', 'Bahrabise Municipality', 'Melamchi Municipality', 'Indrawati Rural Municipality', 'Jugal Rural Municipality', 'Panchpokhari Thangpal Rural Municipality', 'Bhotekoshi Rural Municipality', 'Lisankhu Pakhar Rural Municipality', 'Sunkoshi Rural Municipality', 'Helambu Rural Municipality', 'Tripurasundari Rural Municipality', 'Balefi Rural Municipality']
            ],
            'Gandaki Province' => [
                'Baglung' => ['Baglung Municipality', 'Galkot Municipality', 'Jaimuni Municipality', 'Dhorpatan Municipality', 'Bareng Rural Municipality', 'Kathekhola Rural Municipality', 'Taman Khola Rural Municipality', 'Tara Khola Rural Municipality', 'Nisikhola Rural Municipality', 'Badigad Rural Municipality'],
                'Gorkha' => ['Gorkha Municipality', 'Palungtar Municipality', 'Sulikot Rural Municipality', 'Siranchok Rural Municipality', 'Ajirkot Rural Municipality', 'Arughat Rural Municipality', 'Gandaki Rural Municipality', 'Chum Nubri Rural Municipality', 'Dharche Rural Municipality', 'Bhimsen Thapa Rural Municipality', 'Sahid Lakhan Rural Municipality'],
                'Kaski' => ['Pokhara Metropolitan City', 'Annapurna Rural Municipality', 'Machhapuchhre Rural Municipality', 'Madi Rural Municipality', 'Rupa Rural Municipality'],
                'Lamjung' => ['Besisahar Municipality', 'Madhya Nepal Municipality', 'Rainas Municipality', 'Sundarbazar Municipality', 'Kwaholasothar Rural Municipality', 'Dordi Rural Municipality', 'Dudhpokhari Rural Municipality', 'Marsyangdi Rural Municipality'],
                'Manang' => ['Chame Rural Municipality', 'Narpa Bhumi Rural Municipality', 'Nason Rural Municipality', 'Manang Ngisyang Rural Municipality'],
                'Mustang' => ['Gharapjhong Rural Municipality', 'Thasang Rural Municipality', 'Baragung Muktichhetra Rural Municipality', 'Lomanthang Rural Municipality', 'Lo-Ghekar Damodarkunda Rural Municipality'],
                'Myagdi' => ['Beni Municipality', 'Annapurna Rural Municipality', 'Dhaulagiri Rural Municipality', 'Mangala Rural Municipality', 'Malika Rural Municipality', 'Raghuganga Rural Municipality'],
                'Nawalpur' => ['Kawasoti Municipality', 'Gaindakot Municipality', 'Devachuli Municipality', 'Madhyabindu Municipality', 'Baudhikali Rural Municipality', 'Bulingtar Rural Municipality', 'Binayi Tribeni Rural Municipality', 'Hupsekot Rural Municipality'],
                'Parbat' => ['Kushma Municipality', 'Phalebas Municipality', 'Jaljala Rural Municipality', 'Paiyun Rural Municipality', 'Mahashila Rural Municipality', 'Modi Rural Municipality', 'Bihadi Rural Municipality'],
                'Syangja' => ['Putalibazar Municipality', 'Galyang Municipality', 'Chapakot Municipality', 'Bhimad Municipality', 'Waling Municipality', 'Arjunchhauf Rural Municipality', 'Kaligandaki Rural Municipality', 'Phedikhola Rural Municipality', 'Biruwa Rural Municipality', 'Harinas Rural Municipality', 'Aandhikhola Rural Municipality'],
                'Tanahun' => ['Byas Municipality', 'Bhanu Municipality', 'Bhimad Municipality', 'Shuklagandaki Municipality', 'Anbukhaireni Rural Municipality', 'Devghat Rural Municipality', 'Bandipur Rural Municipality', 'Rishing Rural Municipality', 'Ghiring Rural Municipality', 'Myagde Rural Municipality']
            ],
            'Lumbini Province' => [
                'Arghakhanchi' => ['Sandhikharka Municipality', 'Shitaganga Municipality', 'Bhumikasthan Municipality', 'Chhatradev Rural Municipality', 'Panini Rural Municipality', 'Malarani Rural Municipality'],
                'Banke' => ['Nepalgunj Sub-Metropolitan City', 'Kohalpur Municipality', 'Narainapur Rural Municipality', 'Rapti Sonari Rural Municipality', 'Baijanath Rural Municipality', 'Khajura Rural Municipality', 'Duduwa Rural Municipality', 'Janki Rural Municipality'],
                'Bardiya' => ['Gulariya Municipality', 'Madhuwan Municipality', 'Rajapur Municipality', 'Thakurbaba Municipality', 'Bansgadhi Municipality', 'Barbardiya Municipality', 'Geruwa Rural Municipality', 'Badhaiyatal Rural Municipality'],
                'Dang' => ['Ghorahi Sub-Metropolitan City', 'Tulsipur Sub-Metropolitan City', 'Lamahi Municipality', 'Gadhawa Rural Municipality', 'Rajpur Rural Municipality', 'Shantinagar Rural Municipality', 'Sisne Rural Municipality', 'Banglachuli Rural Municipality', 'Dangisharan Rural Municipality', 'Rapti Rural Municipality'],
                'Gulmi' => ['Tamghas Municipality', 'Resunga Municipality', 'Musikot Municipality', 'Isma Rural Municipality', 'Kaligandaki Rural Municipality', 'Gulmidarbar Rural Municipality', 'Satyawati Rural Municipality', 'Chandrakot Rural Municipality', 'Rurukshetra Rural Municipality', 'Chhatrakot Rural Municipality', 'Dhurkot Rural Municipality', 'Madane Rural Municipality', 'Malika Rural Municipality'],
                'Kapilvastu' => ['Kapilvastu Municipality', 'Banganga Municipality', 'Buddhabhumi Municipality', 'Shivaraj Municipality', 'Krishnanagar Municipality', 'Maharajgunj Municipality', 'Mayadevi Rural Municipality', 'Yashodhara Rural Municipality', 'Suddhodhan Rural Municipality', 'Bijaynagar Rural Municipality'],
                'Parasi' => ['Ramgram Municipality', 'Sunwal Municipality', 'Bardaghat Municipality', 'Sarawal Rural Municipality', 'Palhinandan Rural Municipality', 'Pratappur Rural Municipality', 'Susta Rural Municipality'],
                'Palpa' => ['Tansen Municipality', 'Rampur Municipality', 'Nisdi Rural Municipality', 'Purbakhola Rural Municipality', 'Rambha Rural Municipality', 'Mathagadhi Rural Municipality', 'Tinau Rural Municipality', 'Bagnaskali Rural Municipality', 'Ribdikot Rural Municipality', 'Raina Devi Chhahara Rural Municipality'],
                'Pyuthan' => ['Pyuthan Municipality', 'Swargadwari Municipality', 'Gaumukhi Rural Municipality', 'Mandavi Rural Municipality', 'Sarumarani Rural Municipality', 'Mallarani Rural Municipality', 'Naubahini Rural Municipality', 'Jhimruk Rural Municipality', 'Airawati Rural Municipality'],
                'Rolpa' => ['Rolpa Municipality', 'Runtigadhi Rural Municipality', 'Triveni Rural Municipality', 'Sunil Smriti Rural Municipality', 'Lungri Rural Municipality', 'Duikholi Rural Municipality', 'Madi Rural Municipality', 'Thawang Rural Municipality', 'Sunchhari Rural Municipality', 'Sukidaha Rural Municipality'],
                'Rupandehi' => ['Butwal Sub-Metropolitan City', 'Siddharthanagar Municipality', 'Sainamaina Municipality', 'Tilottama Municipality', 'Devdaha Municipality', 'Lumbini Sanskritik Municipality', 'Gaidahawa Rural Municipality', 'Kanchan Rural Municipality', 'Kotahimai Rural Municipality', 'Marchawari Rural Municipality', 'Mayadevi Rural Municipality', 'Omsatiya Rural Municipality', 'Rohini Rural Municipality', 'Sammarimai Rural Municipality', 'Siyari Rural Municipality', 'Suddhodhan Rural Municipality']
            ],
            'Karnali Province' => [
                'Dailekh' => ['Narayan Municipality', 'Dullu Municipality', 'Chamunda Bindrasaini Municipality', 'Aathbis Municipality', 'Bhagawatimai Rural Municipality', 'Gurans Rural Municipality', 'Dungeshwar Rural Municipality', 'Naumule Rural Municipality', 'Mahabu Rural Municipality', 'Bhairabi Rural Municipality', 'Thantikandh Rural Municipality'],
                'Dolpa' => ['Thuli Bheri Municipality', 'Tripurasundari Municipality', 'Dolpo Buddha Rural Municipality', 'Shey Phoksundo Rural Municipality', 'Jagadulla Rural Municipality', 'Mudkechula Rural Municipality', 'Kaike Rural Municipality', 'Chharka Tangsong Rural Municipality'],
                'Humla' => ['Simkot Rural Municipality', 'Namkha Rural Municipality', 'Kharpunath Rural Municipality', 'Sarkegad Rural Municipality', 'Chankheli Rural Municipality', 'Adanchuli Rural Municipality', 'Tanjakot Rural Municipality'],
                'Jajarkot' => ['Bheri Municipality', 'Chhedagad Municipality', 'Nalgad Municipality', 'Barekot Rural Municipality', 'Kushe Rural Municipality', 'Junichande Rural Municipality', 'Shivalaya Rural Municipality'],
                'Jumla' => ['Chandannath Municipality', 'Kankasundari Rural Municipality', 'Sinja Rural Municipality', 'Hima Rural Municipality', 'Tila Rural Municipality', 'Guthichaur Rural Municipality', 'Tatopani Rural Municipality', 'Patarasi Rural Municipality'],
                'Kalikot' => ['Manma Municipality', 'Raskot Municipality', 'Tilagufa Municipality', 'Khandachakra Municipality', 'Pachaljharana Rural Municipality', 'Sanni Triveni Rural Municipality', 'Narharinath Rural Municipality', 'Subha Kalika Rural Municipality', 'Mahawai Rural Municipality'],
                'Mugu' => ['Chhayanath Rara Municipality', 'Mugum Karmarong Rural Municipality', 'Soru Rural Municipality', 'Khatyad Rural Municipality'],
                'Rukum West' => ['Musikot Municipality', 'Chaurjahari Municipality', 'Aathbiskot Municipality', 'Banphikot Rural Municipality', 'Tribeni Rural Municipality', 'Sani Bheri Rural Municipality'],
                'Salyan' => ['Sharada Municipality', 'Bagchaur Municipality', 'Bangad Kupinde Municipality', 'Kalimati Rural Municipality', 'Tribeni Rural Municipality', 'Kapurkot Rural Municipality', 'Chatreshwari Rural Municipality', 'Kumakh Rural Municipality', 'Siddha Kumakh Rural Municipality', 'Darma Rural Municipality'],
                'Surkhet' => ['Birendranagar Municipality', 'Bheriganga Municipality', 'Gurbhakot Municipality', 'Panchapuri Municipality', 'Lekbeshi Municipality', 'Chaukune Rural Municipality', 'Barahatal Rural Municipality', 'Chingad Rural Municipality', 'Simta Rural Municipality']
            ],
            'Sudurpashchim Province' => [
                'Achham' => ['Mangalsen Municipality', 'Kamalbazar Municipality', 'Sanfebagar Municipality', 'Panchadeval Binayak Municipality', 'Chaurpati Rural Municipality', 'Mellekh Rural Municipality', 'Bannigadhi Jayagadh Rural Municipality', 'Ramaroshan Rural Municipality', 'Dhankari Rural Municipality', 'Turmakhand Rural Municipality'],
                'Baitadi' => ['Dasharathchand Municipality', 'Patan Municipality', 'Melauli Municipality', 'Purchaudi Municipality', 'Sunarya Rural Municipality', 'Sigas Rural Municipality', 'Shivanath Rural Municipality', 'Pancheshwar Rural Municipality', 'Dogadakedar Rural Municipality', 'Dilasaini Rural Municipality'],
                'Bajhang' => ['Jayaprithvi Municipality', 'Bungal Municipality', 'Talkot Rural Municipality', 'Masta Rural Municipality', 'Khaptadchhanna Rural Municipality', 'Thalara Rural Municipality', 'Bitthadchir Rural Municipality', 'Surma Rural Municipality', 'Chhabispathibhera Rural Municipality', 'Durgathali Rural Municipality', 'Kedarsyuh Rural Municipality', 'Saipal Rural Municipality'],
                'Bajura' => ['Badimalika Municipality', 'Tribeni Municipality', 'Budhiganga Municipality', 'Budhinanda Municipality', 'Gaumul Rural Municipality', 'Pandav Gupha Rural Municipality', 'Swami Kartik Rural Municipality', 'Chhededaha Rural Municipality', 'Himali Rural Municipality'],
                'Dadeldhura' => ['Amargadhi Municipality', 'Parshuram Municipality', 'Aalital Rural Municipality', 'Bhageshwar Rural Municipality', 'Navadurga Rural Municipality', 'Ajayameru Rural Municipality', 'Ganyapdhura Rural Municipality'],
                'Darchula' => ['Khalanga Municipality', 'Mahakali Municipality', 'Shailyashikhar Municipality', 'Malikarjun Rural Municipality', 'Apihimal Rural Municipality', 'Duhun Rural Municipality', 'Naugad Rural Municipality', 'Marma Rural Municipality', 'Lekam Rural Municipality', 'Byas Rural Municipality'],
                'Doti' => ['Dipayal Silgadhi Municipality', 'Shikhar Municipality', 'Purbichauki Rural Municipality', 'Badikedar Rural Municipality', 'Jorayal Rural Municipality', 'Sayal Rural Municipality', 'Aadarsha Rural Municipality', 'Dr. K.I. Singh Rural Municipality', 'Bogatan Rural Municipality'],
                'Kailali' => ['Dhangadhi Sub-Metropolitan City', 'Tikapur Municipality', 'Ghodaghodi Municipality', 'Lamkichuha Municipality', 'Bhajani Municipality', 'Godawari Municipality', 'Gauriganga Municipality', 'Janaki Rural Municipality', 'Bardagoriya Rural Municipality', 'Mohanyal Rural Municipality', 'Kailari Rural Municipality', 'Joshipur Rural Municipality', 'Chure Rural Municipality'],
                'Kanchanpur' => ['Bhimdatta Municipality', 'Punarbas Municipality', 'Bedkot Municipality', 'Mahakali Municipality', 'Shuklaphanta Municipality', 'Belauri Municipality', 'Krishnapur Municipality', 'Beldandi Rural Municipality', 'Laljhadi Rural Municipality']
            ]
        ];
    }
}
