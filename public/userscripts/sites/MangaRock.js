(function(sites) {
	/**
	 * MangaRock
	 * @type {SiteObject}
	 */
	sites['mangarock.com'] = {
		preInit : function(callback) {
			let currentURL = location.pathname;
			setInterval(function(){
				if(currentURL !== location.pathname) {
					currentURL = location.pathname;
					if(/\/chapter\/mrs-chapter-/.test(currentURL)) {
						location.reload();
					}
				}
			}, 1000);

			let dfd = $.Deferred();
			let checkSelector = setInterval(function () {
				if ($('.pageMangaReader #page-content').text() !== '') {
					//AJAX has loaded, resolve deferred.
					dfd.resolve();
					clearInterval(checkSelector);
				} else {
					console.log('trackr - Waiting for initial page load...');
				}
			}, 1000);
			dfd.done(() => {
				callback();
			});
		},
		setObjVars : function() {
			let _this = this;

			//this.page_count    = $('.list-switcher-2 > li > select[name=category_type]').get(0).length;
			this.title         = this.segments[2].substr(10);
			let chapterID      = this.segments[4].substr(12);
			this.chapter       = chapterID + ':--:' + $('div[data-test="reader-manga-name"] + div select > option:selected').text().replace(/^(.*?):.*?$/, '$1').replace(/Chapter /g, 'c').replace(/Vol\.([0-9]+) /, 'v$1/').trim();

			this.title_url   = `${this.https}://mangarock.com/manga/mrs-serie-${this.title}`;
			this.chapter_url = `${this.title_url}/chapter/mrs-chapter-${chapterID}`;

			let tempList = window.generateChapterList($('div[data-test="reader-manga-name"] + div select:first > option'), 'value');
			this.chapterList = Object.keys(tempList).reduce(function(result, key) {
				result[`${_this.title_url}/chapter/${key}`] = tempList[key];
				return result;
			}, {});
			this.chapterListCurrent = this.chapter_url;
		},
		stylize : function() {
			//MangaRock uses AJAX when changing chapters,
			$('#app-layout-container').on('click', 'div[class*="slick-initialized slick-slider"] + div > a:contains("Prev Chapter")', function(e) {
				e.preventDefault();

				$('#TrackerBarLayout').find('> a:contains("Previous")').click();
			}).on('click', 'div[class*="slick-initialized slick-slider"] + div > a:contains("Next Chapter")', function(e) {
				e.preventDefault();
				$('#TrackerBarLayout').find('> a:contains("Next")').click();
			});
		}
	};

})(window.trackerSites = (window.trackerSites || {}));
