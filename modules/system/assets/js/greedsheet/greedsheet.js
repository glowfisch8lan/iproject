class GradeSheet {

    constructor(table) {
        this.table = table;
    }

    /**
     *   Подсчет среднего балла студента
     * */
    calculateAverageMarks(){
        $(this.table).find("tr").each(function(){

            var index = 0;
            var sum = 0;

            $(this).find('td.marks > a > span').each(function(){
                var mark = $(this).attr('value');
                if(typeof mark != "undefined"){
                    var result = mark.match(/(\d)/g);
                    if(result != null)
                    {

                        for (let i = 0; i < result.length; i++)
                        {

                            sum +=  Number.parseInt(result[i]);
                            index++;
                        }

                    }

                }
            });
            var average = Math.floor((sum/index) * 100) / 100;
            if(isNaN(average)){average = '-';}
            $(this).find('td.average').html('').append('<strong>'+average+'</strong>');
        });
    }

    /**
     *   Подсчет среднего балла дисциплины
     * */
    calculateDisciplinesAverage(){
        var indexArray = [];
        $(this.table).find("tr:eq(1)  > th.disciplines").each(function(){
            indexArray.push($(this).index());
        });
        $(document).find('tr.disciplines-average').html('').append('<td colspan="2"></td>');
        for(let i = 0 ; i < indexArray.length ; i++ )
        {
            var index = 0;
            var sum = 0;

            $(this.table).find("tr.main-content").each(function(){
                $(this).find('td.marks:eq('+i+')').each(function(){

                    $(this).find('a > span').each(function(){
                        var mark = $(this).attr('value');
                        if(typeof mark != "undefined"){
                            var result = mark.match(/(\d)/g);
                            if(result != null)
                            {
                                for (let i = 0; i < result.length; i++)
                                {
                                    sum +=  Number.parseInt(result[i]);
                                    index++;
                                }
                            }
                        }
                    });
                });
            });
            var average = Math.floor((sum/index) * 100) / 100;
            if(isNaN(average)){average = '-';}
            $(this.table).find('tr.disciplines-average').append('<td><strong>'+average+'</strong></td>');

        }
        $(this.table).find('tr.disciplines-average').append('<td class="group-average bg-primary text-white"></td>')
    }

    /**
     *   Подсчет среднего балла группы
     * */
    calculateAverageGroup(){

        var index = 0;
        var sum = 0;

        $(this.table).find('td.average').each(function(){
            var mark = Number.parseInt( $(this).text() );
            if(!isNaN(mark))
            {
                sum += mark;
            }
            index++;
        });
        var average = Math.floor((sum/index) * 100) / 100;
        if(isNaN(average)){average = '-';}
        $(this.table).find('td.group-average').html('<strong>'+average+'</strong>');
    }

    /**
     * Считаем количество оценок по типам
     */
    countMarks(){
        var arr = ['5'];
        arr['5'] = 0;
        arr['4'] = 0;
        arr['3'] = 0;
        arr['2'] = 0;
        arr['2/'] = 0;

        /**
         * Определяем, к каком массиву добавить ++ оценку
         * @param value
         */
        function add(value){
            if(Number(value) === 5){arr['5']++;}
            if(Number(value) === 4){arr['4']++;}
            if(Number(value) === 3){arr['3']++;}
            if(Number(value) === 2){arr['2']++;}
        }

        /**
         * Выводм в <td> количество оценок
         */
        function showResult(){
            $('.count-5').html(arr['5']);
            $('.count-4').html(arr['4']);
            $('.count-3').html(arr['3']);
            $('.count-2').html(arr['2']);
            $('.count-2-corrected').html(arr['2/']);
        }


        $(this.table).find('td.marks').each(function(){
            var mark = $(this).find('span').html();

            if(typeof mark != "undefined") {
                var result = mark.match(/(\d)/g);
                /**
                 *  Если в массиве оценок более 1, то обрабатываем ее как отработанную
                 */
                if(result.length > 1){
                    /**
                     * Если оценка отработана, обрабатываем каждую оценку
                     */
                    for (let i = 0; i < result.length; i++)
                    {
                        /**
                         * Если оценка - 2, то причисляем ее к отработанным двойкам, иначе суммируем другие оценки как положено
                         */
                        if(Number(result[i]) === 2)
                        {
                            arr['2/']++;
                        }
                        else{
                            add(mark)
                        }
                    }
                }
                add(mark);
            }
        });
        showResult();
    }
}