@extends('pages.association')

@section('page-title')
    History
@endsection
@section('title', "History - T.F.V. ‘Professor Francken’")
@php
$breadcrumbs = [
    ['url' => '/association', 'text' => 'Association'],
    ['text' => 'History'],
];
@endphp

@section('content')
    {{-- The history as a timeline, with the most important moments as navy milestones --}}
    {{-- The history in four periods. Each period is folded, and opens with a click on its
         heading --}}
    <div class="history-eras">
        <details class="history-era" id="professor-francken">
            <summary class="history-era__summary">
                <span class="history-era__years">1919–1984</span>
                <span class="history-era__title">Professor Francken</span>
                <span class="history-era__description">The life of Jan Francken and the applied physics programme in Groningen, before the association carried his name.</span>
                <i class="fas fa-chevron-down history-era__chevron" aria-hidden="true"></i>
            </summary>

            <div class="history-timeline">
                <div class="history-entry">
                    <div class="history-entry__year">1919</div>
                    <div class="history-entry__card">
          <p>On March 29, 1919 Jan Carel Francken is born in Soerabaja (now Surabaya), in the Dutch East Indies, at the time a Dutch colony.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1948</div>
                    <div class="history-entry__card">
          <p>In 1948 Francken graduates in applied physics in Delft. His studies took longer than planned: he served six years in the Royal Netherlands East Indies Army (KNIL), and was held prisoner of war by the Japanese army from 1942 to 1945.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1953</div>
                    <div class="history-entry__card">
          <p>In 1953 Francken earns his doctorate with the dissertation “Elektronenoptische afbeelding in de beeldiconoscoop” (electron-optical imaging in the iconoscope). That same year Frits Zernike receives the Nobel Prize for the phase-contrast microscope, which he developed in Groningen.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1957</div>
                    <div class="history-entry__card">
          <p>In 1957 the University of Groningen asks the Minister of Education for permission to hold doctoral exams in applied physics, and in 1958 the request is granted. Professors for the other subjects are found easily, but finding one for applied physics proves harder.
          In November 1961 Jan Francken receives a letter from the nomination committee asking him to become a professor. At the time he is working at Philips on the development of color TV. He accepts, and on July 3, 1962 he is appointed professor, with the main task of setting up the applied physics programme.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1968</div>
                    <div class="history-entry__card">
          <p>At first the programme is housed at the Westersingel, but as the number of students grows rapidly it needs more space and moves to the Antillenstraat. In 1968 it moves again, this time to the Zernike Complex in Paddepoel.</p>
          <p>To link the association to the title of engineer, its name is changed to “Vereniging van Toekomstige Ingenieurs” (Association of Future Engineers). That goal is reached in 1971, and the association is renamed “Vereniging voor Kandidaten Technische Natuurkunde” (Association for Candidates in Applied Physics). Between 1982 and 1984 the programme is reformed and students no longer have to take candidate exams, so this name loses its meaning.</p>
                    </div>
                </div>
            </div>
        </details>

        <details class="history-era" id="first-years">
            <summary class="history-era__summary">
                <span class="history-era__years">1984–1999</span>
                <span class="history-era__title">The first years</span>
                <span class="history-era__description">The founding, the first symposium and foreign trip, the first members room and the first Francken Vrij.</span>
                <i class="fas fa-chevron-down history-era__chevron" aria-hidden="true"></i>
            </summary>

            <div class="history-timeline">
                <div class="history-entry history-entry--milestone">
                    <div class="history-entry__year"></div>
                    <div class="history-entry__card" data-fcc-lattice>
                        <div class="history-entry__date">October 12, 1984</div>
                        <h2 class="history-entry__title">Establishment of T.F.V. ‘Professor Francken’</h2>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1984</div>
                    <div class="history-entry__card">
          <p>On August 1, 1984 Professor Francken retires. After his valedictory lecture on September 18, he is asked whether he would like to lend his name to the association. On Friday October 12, 1984 T.F.V. ‘Professor Francken’ is founded, and in 1985 Professor Francken becomes an honorary member. From the start the association holds a borrel on Friday afternoons in the canteen, as there is no Francken room yet. Jeff de Hosson lends his Mercedes to fetch the snacks, and plays the piano at the borrels while the members sing along. The current logo, the FCC structure with the logo of the university, is introduced in 1988.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1989</div>
                    <div class="history-entry__card">
          <p>To celebrate the lustrum, the first Francken Symposium is organized on May 19, 1989; it has been held every year since. The same year sees the first foreign trip, which also becomes an annual tradition. In 1990 the board chooses young grain jenever from Hooghoudt as its standard business gift.</p>
                    </div>
                </div>
                <div class="history-entry">
                    <div class="history-entry__year">1992</div>
                    <div class="history-entry__card">
          <p>In 1992, the year the applied physics programme turns thirty and publishes a lustrum book, the association gets its first members room: room 5113.0104 in building 13 at Nijenborgh 4. It is open every weekday from 9:00 until the last person leaves. The coffee is free, other food and drinks are written down on lists, and later in the afternoon there is beer and a game of Mex. The association also gets its mascots: Pluis and Truus.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1993</div>
                    <div class="history-entry__card">
          <p>The board of ’93–’94 writes to the Minister of Education asking to add a fifth year to the applied physics programme, and the request is granted. It is also decided that the association needs a magazine of its own: the first edition of the Francken Vrij appears in 1996.</p>
                    </div>
                </div>

                <div class="history-entry history-entry--milestone">
                    <div class="history-entry__year"></div>
                    <div class="history-entry__card" data-fcc-lattice>
                        <div class="history-entry__date">1996</div>
                        <h2 class="history-entry__title">Publication of the first Francken Vrij</h2>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">1998</div>
                    <div class="history-entry__card">
          <p>After the Francken Symposium of 1998, Professor De Hosson is appointed honorary member for his commitment to and support of the association, and receives the matching tie.</p>
                    </div>
                </div>
            </div>
        </details>

        <details class="history-era" id="growing-up">
            <summary class="history-era__summary">
                <span class="history-era__years">2000–2014</span>
                <span class="history-era__title">Growing up</span>
                <span class="history-era__description">Lustrum trips outside Europe, new rooms in building 13, new traditions and the first almanac.</span>
                <i class="fas fa-chevron-down history-era__chevron" aria-hidden="true"></i>
            </summary>

            <div class="history-timeline">
                <div class="history-entry">
                    <div class="history-entry__year">2000</div>
                    <div class="history-entry__card">
          <p>In 2000 the first lustrum trip outside Europe takes place: three weeks in South Africa. For this trip the Stichting Lustrumexcursies Francken is founded, which has organized the foreign trips ever since. Since then it has also been a tradition to travel outside Europe in every lustrum year.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2002</div>
                    <div class="history-entry__card">
          <p>In 2002 the association moves to the ground floor of building 13, into room 5113.0006, which would later become the board room. Couches in the hallway serve as an extension of the room. Later that year the association moves again, to room 5113.0001.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2004</div>
                    <div class="history-entry__card">
          <p>In 2004 the association moves once more, across the hallway to room 5113.0002. This is the room that many still call the old Francken room: the home of the association until the move to the Feringa Building. That same year the first engineers’ symposium, now known as Masters of Engineering, is organized together with the associations TBV Lugus and GTD Bernoulli.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2006</div>
                    <div class="history-entry__card">
          <p>In 2006 the flexible bachelor’s programme starts, and as a result the percentage of women increases significantly. That year the association also starts offering practice sessions for exams, and decides that beer is only allowed after four o’clock: “vier uur, bier uur”. Together with Cover, CB, Lugus, PS, Idun, Bernoulli and FMF, the first Beta Business Days are organized.</p>
                    </div>
                </div>

                <div class="history-entry history-entry--milestone">
                    <div class="history-entry__year"></div>
                    <div class="history-entry__card" data-fcc-lattice>
                        <div class="history-entry__date">January 15, 2007</div>
                        <h2 class="history-entry__title">Jan Francken dies at age 87</h2>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2010</div>
                    <div class="history-entry__card">
          <p>In 2010, after two years at Café Koster, Café Karakter becomes the regular pub of the association. That same year the consultancy event Expedition Strategy is organized for the first time, together with the associations VESTING, Risk and TBV Lugus. The university also introduces the binding study advice: first-year students have to earn 40 study points to be allowed to continue their studies.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2012</div>
                    <div class="history-entry__card">
          <p>2012 is a year of anniversaries: fifty years after Francken started building it, the applied physics programme celebrates its golden jubilee, and honorary member Jeff de Hosson looks back on 35 years as a professor. Amid the celebrations, the committee S[ck]rip(t|t?c)ie organizes the very first Crash &amp; Compile, a programming competition.</p>
          <p>That year the first Francken almanac appears as well. It does not go unnoticed: on May 15, 2013 it wins the UK Almanac Award for the best almanac, and in October 2013 the second edition follows.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2013</div>
                    <div class="history-entry__card">
          <p>In 2013 the first Tour de Francken is held: a drinking contest based on the book <em>De helaasheid der dingen</em>, after years as a Borrelcie project. Many teams take part, but only team “Waanzin” wins the yellow jersey. That same year Café Karakter goes bankrupt and the association moves to Café Jut en Jul, although the external borrels keep the name “karakterborrel”.</p>
                    </div>
                </div>

                <div class="history-entry history-entry--milestone">
                    <div class="history-entry__year"></div>
                    <div class="history-entry__card" data-fcc-lattice>
                        <div class="history-entry__date">June 4, 2014</div>
                        <h2 class="history-entry__title">Gert Eising becomes the first Member of Merit</h2>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2014</div>
                    <div class="history-entry__card">
          <p>In 2014 the association introduces a new honour: Member of Merit. At the general members assembly of June 4, 2014, Gert Eising is the first to receive it, for his contribution to the digitalization of the association. Most notably, he builds the streepsysteem, which keeps track of the food and drinks taken in the members room. Whenever an electronic device at Francken acts up, shouting his name is the most effective solution. That same year the association starts selling second-hand study books.</p>
                    </div>
                </div>
            </div>
        </details>

        <details class="history-era" id="recent-years">
            <summary class="history-era__summary">
                <span class="history-era__years">2015–now</span>
                <span class="history-era__title">Recent years</span>
                <span class="history-era__description">Engineering physics, a bigger board, a pandemic and the move to the Feringa Building.</span>
                <i class="fas fa-chevron-down history-era__chevron" aria-hidden="true"></i>
            </summary>

            <div class="history-timeline">
                <div class="history-entry">
                    <div class="history-entry__year">2015</div>
                    <div class="history-entry__card">
          <p>In 2015 the first Applied Physics dinner for students and staff is organized, and it becomes a yearly event. That same year Jeff de Hosson becomes professor emeritus. A special symposium, attended by many former members, is devoted to his professorship and everything he has done for the association. He steps down as chair of the Francken Symposium, but keeps writing for the Francken Vrij and stays in close contact with the association and its board.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2017</div>
                    <div class="history-entry__card">
          <p>On June 7, 2017 the first commissioner of internal relations joins the board, and for the first time the board has more women than men. A day later the association receives a piano as a gift from its sibling association FMF. Until 2021 the Francken Piano, often out of tune, is a familiar part of the furniture at Francken.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2018</div>
                    <div class="history-entry__card">
          <p>In 2018 a general members assembly decides that Francken will rebrand itself around engineering physics, stepping away from the name applied physics. At the general members assembly of June 6, 2018 a renewed almanac is presented, written in English to keep up with the internationalization of the university and the association.</p>
                    </div>
                </div>

                <div class="history-entry history-entry--milestone">
                    <div class="history-entry__year"></div>
                    <div class="history-entry__card" data-fcc-lattice>
                        <div class="history-entry__date">March 29, 2019</div>
                        <h2 class="history-entry__title">Jeff de Hosson is knighted</h2>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2019</div>
                    <div class="history-entry__card">
          <p>On the day Jan Francken would have turned 100, honorary member Jeff de Hosson is appointed Knight of the Order of the Netherlands Lion. He receives the royal decoration after his valedictory lecture in the Grand Auditorium of the Academy Building.</p>
                    </div>
                </div>

                <div class="history-entry history-entry--milestone">
                    <div class="history-entry__year"></div>
                    <div class="history-entry__card" data-fcc-lattice>
                        <div class="history-entry__date">February 27, 2020</div>
                        <h2 class="history-entry__title">Mark Redeman becomes the second Member of Merit</h2>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2020</div>
                    <div class="history-entry__card">
          <p>Mark Redeman receives the honour for his work on the website and the streepsysteem, among other things. On that same day the first coronavirus infection in the Netherlands is confirmed, and the pandemic soon turns life at the association upside down.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2022</div>
                    <div class="history-entry__card">
          <p>In 2022 Francken reopens its doors. That year, for the first time, the winning team of the Tour de Francken includes a woman.</p>
                    </div>
                </div>

                <div class="history-entry">
                    <div class="history-entry__year">2023</div>
                    <div class="history-entry__card">
          <p>In 2023 ASML gives the association a new coffee machine. The upgrade is such a success that even professors now regularly drop by the members room for a cup. That same year, Commissioner of Educational Affairs becomes a separate function on the board.</p>
                    </div>
                </div>
                <div class="history-entry">
                    <div class="history-entry__year">2024</div>
                    <div class="history-entry__card">
          <p>In 2024 the association moves from building 13 at Nijenborgh 4 to the Feringa Building, its current home.</p>
                    </div>
                </div>
            </div>
        </details>

    </div>

    {{-- A link to a period, such as /association/history#growing-up, opens that period --}}
    <script>
        (function () {
            var period = window.location.hash && document.getElementById(window.location.hash.slice(1));
            if (period && period.tagName === 'DETAILS') {
                period.open = true;
            }
        })();
    </script>
@endsection
