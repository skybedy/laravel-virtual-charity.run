<x-app-layout>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-10">
                  <div class="p-6 text-gray-900 bg-rd-500">
                        <div class="my-3">
                              <h2 class="text-3xl text-orange-500 underline">K čemu to vlastně je?</h2>
                              <p class="text-xl mt-1">
                                    Virtual-run.cz je platforma pro to, čemu se v dnešní době říká virtuální závody, za jejich rozmach se nejspíše nejvíc zasloužil Covid. 
                                    Narozdíl od většiny ostatních služeb tohoto typu, kde primární roli obvykle hrají medaile co nejoriginálnějších tvarů  a všudypřítomné startovné, tak tady
                                    zatím nic podobného nečekejte, protože tato služna je zaměřena daleko více sportovně s akcentem na možnost co nejrychlejšího publikování výsledků a průkaznost a hlavně je zcela ZDARMA.<br>
                                    Samozřejmě existuje spousta jiných služeb zameřěných na virtuální závodění napojených na Stravu, která je jedinou platformou, do které se soustřeďují data od různých
                                    výrobců hardware, tzn. hlavně hodinek, na kterých je tento způsob soutěžení primárně založen, ale na rozdíl od jiných zde výsledkem není pouhé načítání kilometrů. tzn. vyhrává ten, kdo nejvíce naběhá,
                                    ale výsledkem jsou časy na vypsaných distancích. Vzhledem k tomu, že tento způsob nební ani založen ns nějakém zasílání ofocených obrazovek, nebo hodinek, ale probíhá to vsechno 
                                    automaticky (i když manuální způsob je možný také), byla to zajímavá technologická výzva, neboť ne vždy se podaří uběhnout úplně přesně třeba 10km a je třeba mít mechanismus. který si s tím
poradí a samozřejmě také GPS data jsou tématem samy o sobě, protože záleží na kvalitě trackovacího hardware a i na zpracování dat poskytovatelem toho hardware (např. Garmin Connect), od kterého teprve data putují na Stravy, z které pak putují k nám.<br> 

                                    Nemálo věcí zde uvedených se bude v průběhu času nejspíše měnit v závislosti na zájmu o tuto službu jako takovou a poznatcích jak ze strany provozovatele, tak ze strany uživatelů, tzn. běžců.

                              </p>
                        </div>
                        @include('howitworks.partials.events')
                         <div class="mt-10">
                              <h2 class="text-2xl text-orange-500 underline">Kategorie</h2>
                              <p class="text-xl mt-1">
Narozdíl od klasických závodů, kde pořadatelé obvykle nemají na rozdávání a kvůli cenám nemohou často vypisovat tolik kategorií kolik by třeba i sami měli, tady takový problém nemáme a kategorie můžeme dát hezky po 5 letech, ať to je co nejregulernější.
Výjimkou jsou první dvě věkové kategorie, z nich první je známá jako U23, což jsou mladošky a mlaďoši do 22.roku života a pak 23-29 let, která je zároveň kategorií OPEN, do které spadnou všichni ti, kdo nechtějí uvádět rok narození, nebo ti, kterým závodění s mladšími nevadí.<br> Rozhodujícím pro zařazení do kategorie je samozžejmě rok narození a nikoli den narození, 
 nepohlavní se tady nevedou a pro obě tradiční pohlaví jsou pak kategorie vypsány stejně:
<ul class="list-disc text-xl list-inside">
<li>18-22 (U23)</li>
<li>24-29 (OPEN)</li>
<li>30-34</li>
<li>35-39</li>
<li>40-44</li>
<li>45-49</li>
<li>50-54</li>
<li>55-59</li>
<li>60-64</li>
<li>65-69</li>
<li>70-74</li>
<li>75-79</li>
<li>80+</li>
</ul>
</p>
                        </div>


                        @include('howitworks.partials.events')
                        
                        
                      
                       
 
                  </div>
            </div>
        </div>
    </div>

</x-app-layout>
