/* ===================================
 * Don't change anything here!
 * =================================== */

var elements = {
	'1_1_f': 'Full Size Image',
	'1_1_i': 'Full Size Text Invert',
	'intro': 'Intro',
	'i_promo': 'iPhone Promotion',
	'm_promo': 'Macbook Promotion',
	'1_2_p': '1/2 Image Full',
	'1_2_i': '1/2 Text Invert',
	'1_3_p': '1/3 Image Full',
	'1_3_i': '1/3 Text Invert',
	'1_4_p': '1/4 Image Full',
	'1_4_i': '1/4 Text Invert',
	'quote': 'Quotation',
	'1_2_l': '1/2 Image on the Left',
	'1_2_r': '1/2 Image on the Right',
	'1_3_l': '1/3 Image on the Left',
	'1_3_r': '1/3 Image on the Right',
	'1_4_l': '1/4 Image on the Left',
	'1_4_r': '1/4 Image on the Right',
	'1_2_f_l': '1/2 floating Image left',
	'1_2_f_r': '1/2 floating Image right',
	'1_2_l_f': '1/2 Image Features left',
	'1_2_r_f': '1/2 Image Features right',
	'plans': 'Plans',
	'1_1': '1/1 Text',
	'1_2': '1/2 Text',
	'1_3': '1/3 Text',
	'1_4': '1/4 Text',
	'sep': 'Seperator',
	'sep_t': 'Seperator with button'
},

prebuilds = {
	'Big Intro': ['1_1_f', '1_1_i', 'sep', '1_2'],
	'Portfolio': ['m_promo', 'sep', '1_3_p', '1_3_i', 'sep', '1_3_p', '1_3_i', 'sep_t', '1_1'],
	'Big Portfolio': ['i_promo', 'sep', '1_2_p', '1_2_i', 'sep_t', '1_2_l', 'sep', '1_2_r', 'sep_t', 'plans'],
	'Plan': ['1_2_r_f', 'sep', 'plans'],
	'Story': ['1_2_f_l', '1_1'],
	'Info': ['1_1', 'sep', '1_4_p'],
	'Productlist': ['m_promo', 'sep', '1_3_l', 'sep', '1_3_l', 'sep', '1_3_l', 'sep', '1_3_l'],
	'Four Column': ['1_4_p', '1_4', 'sep', '1_4_p', '1_4']
},

colors   = ['00A9E0','00E08E','E0BB00','E04F00','E00084','A500E0',  '00A9E0','00E08E','E0BB00','E04F00','E00084','A500E0',
			'353535','353535','353535','353535','353535','353535',  'FFFFFF','FFFFFF','FFFFFF','FFFFFF','FFFFFF','FFFFFF'];
bgcolors = ['FAFAFA','FAFAFA','FAFAFA','FAFAFA','FAFAFA','FAFAFA',  '151515','151515','151515','151515','151515','151515',
			'D0DD2B','B6C6D7','948C75','FCFBE3','CDD7B6','ADD8C7',  '483030','556270','303048','304848','484830','332335'];
